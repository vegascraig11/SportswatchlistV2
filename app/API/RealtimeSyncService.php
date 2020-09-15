<?php

namespace App\API;

use App\API\MlbApiService;
use App\API\NbaApiService;
use App\API\NcaabApiService;
use App\API\NcaafApiService;
use App\API\NflApiService;
use App\API\NhlApiService;
use App\Events\GameStarted;
use App\Events\GameStatusUpdated;
use App\Game;
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

        if (!($inProgress || $recentlyLive || $inProgressInDb->count() > 0)) {
            return null;
        }

        if (!$inProgress && !$recentlyLive && $inProgressInDb->count() > 0) {
            \Illuminate\Support\Facades\Log::debug('Updating InProgress games from the database...');
            $grouped = $inProgressInDb->groupBy('GameType');
            $grouped->each(function ($league) use ($service) {
                $dates = $league->map(function ($game) {
                    return $game->Date;
                })->unique();

                $dates->each(function ($date) use ($service) {
                    return $this->updateStats($service, $date);
                });
            });

            return;
        }

        \Illuminate\Support\Facades\Log::debug('Updating...');

        return $this->updateStats($service);
    }

    public function updateStats($service, $date = null)
    {
        if (is_null($date)) {
            $date = now()->setTimezone('America/New_York');
        } else {
            $date = Carbon::parse($date, 'America/New_York');
        }

        $games = $service->getGamesByDate($date)
                    ->filter(function ($game) use ($date) {
                        if (isset($game->Updated)) {
                            return $date->diffInMinutes(Carbon::parse($game->Updated, 'America/New_York')) <= 2;
                        }
                        return $date->diffInMinutes(Carbon::parse($game->LastUpdated, 'America/New_York')) <= 2;
                    });

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
        $games->each(function ($game) {
            $sent = Redis::get("{$game->GlobalGameID}-notify");

            if ($sent) {
                return;
            }

            event(new GameStarted($game->toArray()));
            Redis::set("{$game->GlobalGameID}-notify", true);
        });
    }

    public function notifyUpdate($games)
    {
        $games->each(function ($game) {
            event(new GameStatusUpdated($game));
        });
    }
}