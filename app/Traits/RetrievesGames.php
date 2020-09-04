<?php

namespace App\Traits;

use App\Game;
use Carbon\Carbon;

trait RetrievesGames
{
	public function gamesByDate($date = null)
    {
        if (is_null($date)) {
            $date = now()->format('Y-M-d');
        }

        $games = Game::where('GameType', $this->gameType)
                        ->whereDate('Date', Carbon::parse($date)->toDateString())
                        ->with(['homeTeam', 'awayTeam'])
                        ->get();

        if ($games->count() == 0) {
            $games = $this->service->getPopulatedGamesByDate($date);
        }

        return response()->json($games, 200);
    }
}