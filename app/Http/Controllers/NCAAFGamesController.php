<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class NCAAFGamesController extends Controller
{
    public function gamesByDate($date = null)
    {
    	if (is_null($date)) {
    		$date = now()->format('Y-M-d');
    	}

        $teams = Cache::get('ncaaf_teams');
  		$games = Http::withHeaders(['Ocp-Apim-Subscription-Key' => config('services.apiKeys.ncaaf')])
                    ->get("https://api.sportsdata.io/v3/cfb/scores/json/GamesByDate/{$date}")
                    ->json();

        $mappedGames = collect($games)->map(function ($game) use ($teams) {
            $homeTeam = $teams->where('TeamID', $game['HomeTeamID'])->first();
            $awayTeam = $teams->where('TeamID', $game['AwayTeamID'])->first();

    		return [
    			'game_id' => $game['GameID'],
    			'game_time' => $game['DateTime'],
    			'home_team' => [
    				'id' => $game['HomeTeamID'],
    				'name' => $game['HomeTeam'],
    				'full_name' => $homeTeam['Name'],
                    'rotation_number' => $game['HomeRotationNumber'],
    				'score' => $game['HomeTeamScore'],
    				'money_line' => $game['HomeTeamMoneyLine'],
                    'logo' => $homeTeam['TeamLogoUrl']
    			],
    			'away_team' => [
    				'id' => $game['AwayTeamID'],
    				'name' => $game['AwayTeam'],
    				'full_name' => $awayTeam['Name'],
                    'rotation_number' => $game['AwayRotationNumber'],
    				'score' => $game['AwayTeamScore'],
    				'money_line' => $game['AwayTeamMoneyLine'],
                    'logo' => $awayTeam['TeamLogoUrl']
    			],
    			'periods' => $game['Periods'],
                'stadium' => $game['Stadium'],
                'status' => $game['Status']
    		];
    	});

    	return response()->json($mappedGames, 200);
    }
}
