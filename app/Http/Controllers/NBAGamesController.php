<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Promise;
use Illuminate\Http\Request;

class NBAGamesController extends Controller
{
	protected $client;

	public function __construct()
	{
		$this->client = new Client([
			'base_uri' => 'https://api.sportsdata.io/v3/',
			'headers' => [
				'Ocp-Apim-Subscription-Key' => 'ead3c888cb1447e6b50d2a2e204ed2bf'
			]
		]);
	}

    public function gamesByDate($date = null)
    {
    	if (is_null($date)) {
    		$date = now()->format('Y-M-d');
    	}

    	$requests = [
    		'teams' => $this->client->get('nba/scores/json/AllTeams'),
            'stadiums' => $this->client->get('nba/scores/json/Stadiums'),
    		'games' => $this->client->get("nba/scores/json/GamesByDate/{$date}")
    	];

    	$responses = Promise\settle($requests)->wait();

    	$teams = collect(json_decode($responses['teams']['value']->getBody()->getContents()));
        $stadiums = collect(json_decode($responses['stadiums']['value']->getBody()->getContents()));
    	$games = collect(json_decode($responses['games']['value']->getBody()->getContents()));

    	$mappedGames = $games->map(function ($game) use ($teams, $stadiums) {
    		$homeTeam = $teams->filter(function ($team) use ($game) {
    			return $team->TeamID === $game->HomeTeamID;
    		})->first();

    		$awayTeam = $teams->filter(function ($team) use ($game) {
    			return $team->TeamID === $game->AwayTeamID;
    		})->first();

            $stadium = $stadiums->filter(function ($stadium) use ($game) {
                return $stadium->StadiumID === $game->StadiumID;
            })->first();

    		return [
    			'game_id' => $game->GameID,
    			'game_time' => $game->DateTime,
    			'home_team' => [
    				'id' => $game->HomeTeamID,
    				'name' => $game->HomeTeam,
    				'full_name' => $homeTeam->City.' '.$homeTeam->Name,
                    'rotation_number' => $game->HomeRotationNumber,
    				'score' => $game->HomeTeamScore,
    				'money_line' => $game->HomeTeamMoneyLine,
    				'point_spread_money_line' => $game->PointSpreadHomeTeamMoneyLine,
    			],
    			'away_team' => [
    				'id' => $game->AwayTeamID,
    				'name' => $game->AwayTeam,
    				'full_name' => $awayTeam->City.' '.$awayTeam->Name,
                    'rotation_number' => $game->AwayRotationNumber,
    				'score' => $game->AwayTeamScore,
    				'money_line' => $game->AwayTeamMoneyLine,
    				'point_spread_money_line' => $game->PointSpreadAwayTeamMoneyLine,
    			],
    			'quarters' => $game->Quarters,
                'stadium' => $stadium,
                'status' => $game->Status
    		];
    	});

    	return response()->json($mappedGames, 200)->withHeaders([
    		'Access-Control-Allow-Origin' => 'http://localhost:8080'
    	]);
    }
}
