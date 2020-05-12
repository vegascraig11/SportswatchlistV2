<?php

namespace App\Http\Controllers;

use App\Builders\NCAABGameBuilder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class NCAABGamesController extends Controller
{
    public function __construct(NCAABGameBuilder $builder)
    {
        $this->builder = $builder;
    }

    public function gamesByDate($date = null)
    {
    	if (is_null($date)) {
    		$date = now()->format('Y-M-d');
    	}

        $games = Http::withHeaders(['Ocp-Apim-Subscription-Key' => config('services.apiKeys.ncaab')])
                    ->get("https://api.sportsdata.io/v3/cbb/scores/json/GamesByDate/{$date}")
                    ->json();

        $games = $this->builder->build($games);

    	return response()->json($games, 200);
    }
}
