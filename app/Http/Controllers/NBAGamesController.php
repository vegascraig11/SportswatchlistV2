<?php

namespace App\Http\Controllers;

use App\API\NbaApiService;
use App\Traits\RetrievesGames;
use Illuminate\Http\Request;

class NBAGamesController extends Controller
{
    use RetrievesGames;
    
    private $gameType = 'nba';
    private $service;

    public function __construct()
    {
        $this->service = new NbaApiService();
    }
}
