<?php

namespace App\Http\Controllers;

use App\Traits\RetrievesGames;
use Illuminate\Http\Request;

class NCAAFGamesController extends Controller
{
    use RetrievesGames;
    
    private $gameType = 'ncaaf';
}
