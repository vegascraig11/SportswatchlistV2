<?php

namespace App\API;

use App\API\MlbApiService;
use App\API\NbaApiService;
use App\API\NcaabApiService;
use App\API\NcaafApiService;
use App\API\NflApiService;
use App\API\NhlApiService;
use App\Events\GameStatusUpdated;
use App\Events\WatchlistGameStatusChanged;
use App\Game;
use App\Notifications\GameHasStarted;
use App\Watchlist;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class RealtimeSyncService
{
    public function nba()
    {
        $service = new NbaApiService();

        $this->liveStats($service);
    }

    public function mlb()
    {
        $service = new MlbApiService();

        $this->liveStats($service);
    }

    public function nfl()
    {
        $service = new NflApiService();

        $this->liveStats($service);
    }

    public function nhl()
    {
        $service = new NhlApiService();

        $this->liveStats($service);
    }

    public function ncaaf()
    {
        $service = new NcaafApiService();

        $this->liveStats($service);
    }

    public function ncaab()
    {
        $service = new NcaabApiService();

        $this->liveStats($service);
    }

    public function liveStats($service)
    {
        $inProgress = $service->areGamesInProgress();
        $recentlyLive = $service->wasRecentlyLive();
        $inProgressInDb = Game::where('GameType', $service->getLeague())->where('Status', 'InProgress')->get();
        $scheduledInDb = Game::where('GameType', $service->getLeague())
            ->where('Status', 'Scheduled')
            ->whereDate('Date', '<', now()->toDateString())
            ->get();


        if (!($inProgress || $recentlyLive || $inProgressInDb->count() > 0 || $scheduledInDb->count() > 0)) {
            return null;
        }

        if (!$inProgress && !$recentlyLive && $inProgressInDb->count() > 0) {
            $grouped = $inProgressInDb->groupBy('GameType');
            $grouped->each(function ($league) use ($service) {
                $dates = $league->map(function ($game) {
                    return $game->Date;
                })->unique();

                $dates->each(function ($date) use ($service) {
                    return $this->updateStats($service, $date, true);
                });
            });

            return;
        }

        if (!$inProgress && !$recentlyLive && $inProgressInDb->count() == 0 && $scheduledInDb->count() > 0) {
            $grouped = $scheduledInDb->groupBy('GameType');
            $grouped->each(function ($league) use ($service) {
                $dates = $league->map(function ($game) {
                    return $game->Date;
                })->unique();

                $dates->each(function ($date) use ($service) {
                    return $this->updateStats($service, $date, true);
                });
            });

            return;
        }

        return $this->updateStats($service);
    }

    public function updateStats($service, $date = null, $force = false)
    {
        if (is_null($date)) {
            $date = now()->setTimezone('America/New_York');
        } else {
            $date = Carbon::parse($date, 'America/New_York');
        }

        $games = $service->getGamesByDate($date);

        if (!$force) {
            $games = $games->filter(function ($game) use ($date) {
                if (isset($game->Updated)) {
                    return $date->diffInMinutes(Carbon::parse($game->Updated, 'America/New_York')) <= 2;
                }
                return $date->diffInMinutes(Carbon::parse($game->LastUpdated, 'America/New_York')) <= 2;
            });
        }

        if ($games->count() == 0) {
            return null;
        }

        $mapped = $games->map(function ($game) use ($service) {
            return $service->mapGame($game);
        });

        try {
            DB::beginTransaction();
            $mapped->each(function ($game) {
                DB::table('games')
                    ->updateOrInsert(
                        ['GlobalGameID' => $game['GlobalGameID']],
                        $game
                    );
            });
            DB::commit();
            $games = Game::whereIn('GlobalGameID', $mapped->map(function ($game) {
                return $game['GlobalGameID'];
            })->toArray());

            $this->notifyStart($games);
            $this->notifyUpdate($games);
        } catch (\Throwable $exception) {
            report($exception);
            DB::rollback();
            return null;
        }
    }

    public function notifyStart($games)
    {
        if (!$games->count()) {
            return;
        }

        $watchlist = Watchlist::whereIn('game_id', $games->pluck('GlobalGameID')->values())->get();

        foreach($games as $game) {
            $lastStatus = Redis::get("game_{$game->GlobalGameID}_last_status");

            if (!$lastStatus) {
                $lastStatus = $game->Status;
                Redis::set("game_{$game->GlobalGameID}_last_status", $game->Status);
            }

            // Game Start
            if ($lastStatus == 'Scheduled' && $game->Status == 'InProgress') {
                foreach ($watchlist as $g) {
                    if (Redis::get("user_{$g->user->id}-game_{$game->GlobalGameID}-start_notified")) {
                        continue;
                    }

                    $message = $game->awayTeam->name . ' vs ' . $game->homeTeam->name . ' has started.';
                    $g->user->notify(new GameHasStarted($message));
                    event(new WatchlistGameStatusChanged($message, $g->user->id));
                    Redis::set("user_{$g->user->id}-game_{$game->GlobalGameID}-start_notified", true);
                }
            }

            // Game End
            if ($lastStatus == 'InProgress' && array_search($game->Status, ['Final', 'F/OT']) !== false) {
                foreach ($watchlist as $g) {
                    if (Redis::get("user_{$g->user->id}-game_{$game->GlobalGameID}-end_notified")) {
                        continue;
                    }

                    $message = $game->awayTeam->name . ' vs ' . $game->homeTeam->name . ' has ended.';
                    $g->user->notify(new GameHasStarted($message));
                    event(new WatchlistGameStatusChanged($message, $g->user->id));
                    Redis::set("user_{$g->user->id}-game_{$game->GlobalGameID}-end_notified", true);
                }
            }
        }
    }

    public function notifyUpdate($games)
    {
        $games->each(function ($game) {
            event(new GameStatusUpdated($game));
        });
    }
}