<?php

namespace App\Http\Controllers;

use App\API\NcaabApiService;
use App\Traits\RetrievesGames;

class NCAABGamesController extends Controller
{
    use RetrievesGames;

    private $service;
    private $gameType = 'ncaab';

    public function __construct()
    {
        $this->service = new NcaabApiService();
    }
}
