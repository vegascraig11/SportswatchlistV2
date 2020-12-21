<?php

namespace App\API;

use App\API\MlbApiService;
use App\API\NbaApiService;
use App\API\NcaabApiService;
use App\API\NcaafApiService;
use App\API\NflApiService;
use App\API\NhlApiService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SyncService
{
    public function nba()
    {
        $service = new NbaApiService();

        $this->sync($service);
    }

    public function mlb()
    {
        $service = new MlbApiService();

        $this->sync($service);
    }

    public function nfl()
    {
        $service = new NflApiService();

        $this->sync($service);
    }

    public function nhl()
    {
        $service = new NhlApiService();

        $this->sync($service);
    }

    public function ncaaf()
    {
        $service = new NcaafApiService();

        $this->sync($service);
    }

    public function ncaab()
    {
        $service = new NcaabApiService();

        $this->sync($service);
    }

    public function sync($service)
    {
        return $this->updateStats($service);
    }

    public function updateStats($service, $date = null)
    {
        if (is_null($date)) {
            $date = now()->setTimezone('America/New_York');
        } else {
            $date = Carbon::parse($date, 'America/New_York');
        }

        $games = $service->getGamesByDate($date);

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
            return true;
        } catch (\Throwable $exception) {
            report($exception);
            DB::rollback();
            return null;
        }
    }
}