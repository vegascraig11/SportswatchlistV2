<?php

namespace App\Http\Controllers;

use App\Traits\RetrievesGames;
use Illuminate\Http\Request;

class NFLGamesController extends Controller
{
	use RetrievesGames;
	
	private $gameType = 'nfl';
}
