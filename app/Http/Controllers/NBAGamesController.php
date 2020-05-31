<?php

namespace App\Http\Controllers;

use App\Traits\RetrievesGames;
use Illuminate\Http\Request;

class NBAGamesController extends Controller
{
	use RetrievesGames;
	
    private $gameType = 'nba';
}
