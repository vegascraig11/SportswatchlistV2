<?php

namespace App\Http\Controllers;

use App\API\NcaafApiService;
use App\Traits\RetrievesGames;

class NCAAFGamesController extends Controller
{
    use RetrievesGames;
    
    private $gameType = 'ncaaf';
    private $service;

    public function __construct()
    {
        $this->service = new NcaafApiService();
    }
}
