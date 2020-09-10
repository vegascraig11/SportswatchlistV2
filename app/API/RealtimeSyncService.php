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

        if (!($inProgress || $recentlyLive)) {
            return null;
        }

        \Illuminate\Support\Facades\Log::debug('Updating...');

        $games = $service->getGamesByDate(now()->setTimezone('America/New_York'))
                    ->filter(function ($game) { 
                        return now()->setTimezone('America/New_York')->diffInMinutes(Carbon::parse($game->Updated, 'America/New_York')) <= 2;
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