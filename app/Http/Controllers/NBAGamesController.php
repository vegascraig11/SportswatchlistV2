<?php

namespace App\Http\Controllers;

use App\Game;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NBAGamesController extends Controller
{
    private $gameType = 'nba';

    public function gamesByDate($date)
    {
        $games = Game::where('GameType', $this->gameType)
        				->whereDate('Date', Carbon::parse($date)->toDateString())
                        ->with(['homeTeam', 'awayTeam', 'stadium'])
                        ->get();

        return response()->json($games, 200);
    }
}
