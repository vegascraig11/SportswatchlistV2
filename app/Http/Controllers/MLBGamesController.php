<?php

namespace App\Http\Controllers;

use App\API\MlbApiService;
use App\Traits\RetrievesGames;
use Illuminate\Http\Request;

class MLBGamesController extends Controller
{
    use RetrievesGames;
    
    private $gameType = 'mlb';
    private $service;

    public function __construct()
    {
        $this->service = new MlbApiService();
    }
}
