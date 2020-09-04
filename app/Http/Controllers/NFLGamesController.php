<?php

namespace App\Http\Controllers;

use App\API\NflApiService;
use App\Traits\RetrievesGames;
use Illuminate\Http\Request;

class NFLGamesController extends Controller
{
    use RetrievesGames;
    
    private $gameType = 'nfl';
    private $service;

    public function __construct()
    {
        $this->service = new NflApiService();
    }
}
