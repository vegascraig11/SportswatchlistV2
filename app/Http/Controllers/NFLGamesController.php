<?php

namespace App\Http\Controllers;

use App\Game;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NFLGamesController extends Controller
{
	private $gameType = 'nfl';

    public function gamesByDate($date)
    {
    	$allGames = Game::where('GameType', $this->gameType)->get();

    	$games = $allGames->where('Date', Carbon::parse($date))->values()->toArray();

    	return response()->json($games, 200);
    }
}
