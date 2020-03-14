<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Promise;
use GuzzleHttp\HandlerStack;
use Illuminate\Http\Request;
use Brightfish\CachingGuzzle\Middleware;

class NCAAFGamesController extends Controller
{
	protected $client;

	public function __construct()
	{
        $store = app('cache')->store('database'); // Laravel
        $handler = new Middleware($store, 3600 * 24);
        $stack = HandlerStack::create();
        $stack->push($handler);

		$this->client = new Client([
            'handler' => $stack,
            'cache_ttl' => 3600 * 24, // 24 Hours
			'base_uri' => 'https://api.sportsdata.io/v3/',
			'headers' => [
				'Ocp-Apim-Subscription-Key' => '4ff3118cd6b94c2bbc47b0df2901a4aa'
			]
		]);
	}

    public function gamesByDate($date = null)
    {
    	if (is_null($date)) {
    		$date = now()->format('Y-M-d');
    	}

    	$requests = [
    		'teams' => $this->client->get('cfb/scores/json/teams'),
    		'games' => $this->client->get("cfb/scores/json/GamesByDate/{$date}", ['cache' => false])
    	];

    	$responses = Promise\settle($requests)->wait();

    	$teams = collect(json_decode($responses['teams']['value']->getBody()->getContents()));
    	$games = collect(json_decode($responses['games']['value']->getBody()->getContents()));

        $mappedGames = $games->map(function ($game) use ($teams) {
    		$homeTeam = $teams->filter(function ($team) use ($game) {
    			return $team->TeamID === $game->HomeTeamID;
    		})->first();

    		$awayTeam = $teams->filter(function ($team) use ($game) {
    			return $team->TeamID === $game->AwayTeamID;
    		})->first();

    		return [
    			'game_id' => $game->GameID,
    			'game_time' => $game->DateTime,
    			'home_team' => [
    				'id' => $game->HomeTeamID,
    				'name' => $game->HomeTeam,
    				'full_name' => $homeTeam->Name,
                    'rotation_number' => $game->HomeRotationNumber,
    				'score' => $game->HomeTeamScore,
    				'money_line' => $game->HomeTeamMoneyLine,
                    'logo' => $homeTeam->TeamLogoUrl
    			],
    			'away_team' => [
    				'id' => $game->AwayTeamID,
    				'name' => $game->AwayTeam,
    				'full_name' => $awayTeam->Name,
                    'rotation_number' => $game->AwayRotationNumber,
    				'score' => $game->AwayTeamScore,
    				'money_line' => $game->AwayTeamMoneyLine,
                    'logo' => $awayTeam->TeamLogoUrl
    			],
    			'periods' => $game->Periods,
                'stadium' => $game->Stadium,
                'status' => $game->Status
    		];
    	});

    	return response()->json($mappedGames, 200);
    }
}
