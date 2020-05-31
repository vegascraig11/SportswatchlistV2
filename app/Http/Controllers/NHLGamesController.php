<?php

namespace App\Http\Controllers;

use App\Traits\RetrievesGames;
use Illuminate\Http\Request;

class NHLGamesController extends Controller
{
    use RetrievesGames;
    
    private $gameType = 'nhl';
}
