<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class MLBGamesController extends Controller
{
    public function gamesByDate($date = null)
    {
    	if (is_null($date)) {
    		$date = now()->format('Y-M-d');
    	}

        $teams = Cache::get('mlb_teams');
        $stadiums = Cache::get('mlb_stadiums');
    	$games = Http::withHeaders(['Ocp-Apim-Subscription-Key' => config('services.apiKeys.mlb')])
                    ->get("https://api.sportsdata.io/v3/mlb/scores/json/GamesByDate/{$date}")
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
    				'runs' => $game['HomeTeamRuns'],
                    'errors' => $game['HomeTeamErrors'],
                    'hits' => $game['HomeTeamHits'],
    				'money_line' => $game['HomeTeamMoneyLine'],
    				'point_spread_money_line' => $game['PointSpreadHomeTeamMoneyLine'],
                    'logo' => $homeTeam['WikipediaLogoUrl']
    			],
    			'away_team' => [
    				'id' => $game['AwayTeamID'],
    				'name' => $game['AwayTeam'],
    				'full_name' => $awayTeam['City'].' '.$awayTeam['Name'],
                    'rotation_number' => $game['AwayRotationNumber'],
    				'runs' => $game['AwayTeamRuns'],
                    'errors' => $game['AwayTeamErrors'],
                    'hits' => $game['AwayTeamHits'],
    				'money_line' => $game['AwayTeamMoneyLine'],
    				'point_spread_money_line' => $game['PointSpreadAwayTeamMoneyLine'],
                    'logo' => $awayTeam['WikipediaLogoUrl']
    			],
    			'innings' => $game['Innings'],
                'stadium' => $stadium,
                'status' => $game['Status'],
                'winningPitcher' => $game['WinningPitcher'],
                'losingPitcher' => $game['LosingPitcher']
    		];
    	});

    	return response()->json($mappedGames, 200);
    }
}
