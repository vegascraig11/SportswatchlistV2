<?php

namespace App\Http\Controllers;

use Brightfish\CachingGuzzle\Middleware;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Promise;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class WatchlistController extends Controller
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
    ]);
  }

  public function index()
  {
    $games = auth()->user()->watchlist;
    // game_id, game_type, game_time

    $mapped = $games->map(function ($game) {
      $date = Carbon::parse($game->game_time)->format('Y-M-d');

      switch ($game->game_type) {
        case 'nba':
          $gamesOnThatDay = Cache::get("nba_games_{$date}", function () use ($date) {
            return Http::withHeaders(['Ocp-Apim-Subscription-Key' => config('services.apiKeys.nba')])
                    ->get("https://api.sportsdata.io/v3/nba/scores/json/GamesByDate/{$date}")
                    ->json();
          });
          
          $gameDetails = collect($gamesOnThatDay)->where('GameID', $game->game_id)->first();
          $teams = Cache::get('nba_teams');
          $stadiums = Cache::get('nba_stadiums');

          $homeTeam = $teams->where('TeamID', $gameDetails['HomeTeamID'])->first();
          $awayTeam = $teams->where('TeamID', $gameDetails['AwayTeamID'])->first();
          $stadium = $stadiums->where('StadiumID', $gameDetails['StadiumID'])->first();

          $game = $game->toArray();
          $game['details'] = [
            'game_id' => $gameDetails['GameID'],
            'game_time' => $gameDetails['DateTime'],
            'home_team' => [
              'id' => $gameDetails['HomeTeamID'],
              'name' => $gameDetails['HomeTeam'],
              'full_name' => $homeTeam['City'].' '.$homeTeam['Name'],
              'rotation_number' => $gameDetails['HomeRotationNumber'],
              'score' => $gameDetails['HomeTeamScore'],
              'money_line' => $gameDetails['HomeTeamMoneyLine'],
              'point_spread_money_line' => $gameDetails['PointSpreadHomeTeamMoneyLine'],
              'logo' => $homeTeam['WikipediaLogoUrl']
            ],
            'away_team' => [
              'id' => $gameDetails['AwayTeamID'],
              'name' => $gameDetails['AwayTeam'],
              'full_name' => $awayTeam['City'].' '.$awayTeam['Name'],
              'rotation_number' => $gameDetails['AwayRotationNumber'],
              'score' => $gameDetails['AwayTeamScore'],
              'money_line' => $gameDetails['AwayTeamMoneyLine'],
              'point_spread_money_line' => $gameDetails['PointSpreadAwayTeamMoneyLine'],
              'logo' => $awayTeam['WikipediaLogoUrl']
            ],
            'over_under' => $gameDetails['OverUnder'],
            'quarters' => $gameDetails['Quarters'],
            'stadium' => $stadium,
            'status' => $gameDetails['Status']
          ];

          return $game;
        
        default:
          # code...
          break;
      }
    });

    return response()->json($mapped, 200);
  }

  public function store()
  {
    $watchlist = auth()->user()->watchlist()->create([
      'game_id' => request('gameId'),
      'game_type' => request('gameType'),
      'game_time' => request('gameTime')
    ]);

    return response()->json($watchlist, 201);
  }
}
