<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class NHLGamesController extends Controller
{
    public function gamesByDate($date = null)
    {
    	if (is_null($date)) {
    		$date = now()->format('Y-M-d');
    	}

        $teams = Cache::get('nhl_teams');
        $stadiums = Cache::get('nhl_stadiums');
        
    	$games = Http::withHeaders(['Ocp-Apim-Subscription-Key' => config('services.apiKeys.nhl')])
                    ->get("https://api.sportsdata.io/v3/nhl/scores/json/GamesByDate/{$date}")
                    ->json();

    	$mappedGames = collect($games)->map(function ($game) use ($teams, $stadiums) {
            $homeTeam = $teams->where('TeamID', $game['HomeTeamID'])->first();
            $awayTeam = $teams->where('TeamID', $game['AwayTeamID'])->first();
            $stadium = $stadiums->where('StadiumID', $game['StadiumID'])->first();

    		return [
    			'game_id' => $game['GameID'],
    			'game_time' => $game['DateTime'],
    			'home_team' => [
    				'id' => $game['HomeTeamID'],
    				'name' => $game['HomeTeam'],
    				'full_name' => $homeTeam['City'].' '.$homeTeam['Name'],
                    'rotation_number' => $game['HomeRotationNumber'],
    				'score' => $game['HomeTeamScore'],
    				'money_line' => $game['HomeTeamMoneyLine'],
    				'point_spread_money_line' => $game['PointSpreadHomeTeamMoneyLine'],
                    'logo' => $homeTeam['WikipediaLogoUrl']
    			],
    			'away_team' => [
    				'id' => $game['AwayTeamID'],
    				'name' => $game['AwayTeam'],
    				'full_name' => $awayTeam['City'].' '.$awayTeam['Name'],
                    'rotation_number' => $game['AwayRotationNumber'],
    				'score' => $game['AwayTeamScore'],
    				'money_line' => $game['AwayTeamMoneyLine'],
    				'point_spread_money_line' => $game['PointSpreadAwayTeamMoneyLine'],
                    'logo' => $awayTeam['WikipediaLogoUrl']
    			],
    			// 'quarters' => $game['Quarters'],
                'stadium' => $stadium,
                'status' => $game['Status']
    		];
    	});

    	return response()->json($mappedGames, 200);
    }
}
