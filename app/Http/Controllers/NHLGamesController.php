<?php

namespace App\Http\Controllers;

use App\API\NhlApiService;
use App\Traits\RetrievesGames;
use Illuminate\Http\Request;

class NHLGamesController extends Controller
{
    use RetrievesGames;
    
    private $gameType = 'nhl';
    private $service;

    public function __construct()
    {
        $this->service = new NhlApiService();
    }
}
