<?php

namespace App\Http\Controllers;

use App\Traits\RetrievesGames;
use Illuminate\Http\Request;

class MLBGamesController extends Controller
{
    use RetrievesGames;
    
	private $gameType = 'mlb';
}
