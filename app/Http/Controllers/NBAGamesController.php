<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use App\Builders\NBAGameBuilder;

class NBAGamesController extends Controller
{
    public function __construct(NBAGameBuilder $builder)
    {
        $this->builder = $builder;
    }

    public function gamesByDate($date = null)
    {
    	if (is_null($date)) {
    		$date = now()->format('Y-M-d');
    	}

        Cache::remember("nba_games_{$date}", 5, function () use ($date) {
            return Http::withHeaders(['Ocp-Apim-Subscription-Key' => config('services.apiKeys.nba')])
                    ->get("https://api.sportsdata.io/v3/nba/scores/json/GamesByDate/{$date}")
                    ->json();
        });
        $games = Cache::get("nba_games_{$date}");

    	$games = $this->builder->build($games);

    	return response()->json($games, 200);
    }
}
