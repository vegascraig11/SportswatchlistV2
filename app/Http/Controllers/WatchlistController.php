<?php

namespace App\Http\Controllers;

use App\Builders\MLBGameBuilder;
use App\Builders\NBAGameBuilder;
use App\Builders\NCAABGameBuilder;
use App\Builders\NCAAFGameBuilder;
use App\Builders\NHLGameBuilder;
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

  public function __construct(
    NBAGameBuilder $nbaBuilder, 
    NCAABGameBuilder $ncaabBuilder,
    NCAAFGameBuilder $ncaafBuilder,
    NHLGameBuilder $nhlBuilder,
    MLBGameBuilder $mlbBuilder
  )
  {
    $this->nbaBuilder = $nbaBuilder;
    $this->ncaabBuilder = $ncaabBuilder;
    $this->ncaafBuilder = $ncaafBuilder;
    $this->nhlBuilder = $nhlBuilder;
    $this->mlbBuilder = $mlbBuilder;

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

          $game = $game->toArray();
          $game['details'] = $this->nbaBuilder->buildGame($gameDetails);

          return $game;

        case 'ncaab':
          $gamesOnThatDay = Cache::get("ncaab_games_{$date}", function () use ($date) {
            return Http::withHeaders(['Ocp-Apim-Subscription-Key' => config('services.apiKeys.ncaab')])
                    ->get("https://api.sportsdata.io/v3/cbb/scores/json/GamesByDate/{$date}")
                    ->json();
          });

          $gameDetails = collect($gamesOnThatDay)->where('GameID', $game->game_id)->first();

          $game = $game->toArray();
          $game['details'] = $this->ncaabBuilder->buildGame($gameDetails);

          return $game;

        case 'ncaaf':
          $gamesOnThatDay = Cache::get("ncaaf_games_{$date}", function () use ($date) {
            return Http::withHeaders(['Ocp-Apim-Subscription-Key' => config('services.apiKeys.ncaaf')])
                    ->get("https://api.sportsdata.io/v3/cfb/scores/json/GamesByDate/{$date}")
                    ->json();
          });

          $gameDetails = collect($gamesOnThatDay)->where('GameID', $game->game_id)->first();

          $game = $game->toArray();
          $game['details'] = $this->ncaabBuilder->buildGame($gameDetails);

          return $game;

        case 'nhl':
          $gamesOnThatDay = Cache::get("nhl_games_{$date}", function () use ($date) {
            return Http::withHeaders(['Ocp-Apim-Subscription-Key' => config('services.apiKeys.nhl')])
                    ->get("https://api.sportsdata.io/v3/nhl/scores/json/GamesByDate/{$date}")
                    ->json();
          });

          $gameDetails = collect($gamesOnThatDay)->where('GameID', $game->game_id)->first();

          $game = $game->toArray();
          $game['details'] = $this->ncaabBuilder->buildGame($gameDetails);

          return $game;

        case 'mlb':
          $gamesOnThatDay = Cache::get("nhl_games_{$date}", function () use ($date) {
            return Http::withHeaders(['Ocp-Apim-Subscription-Key' => config('services.apiKeys.mlb')])
                    ->get("https://api.sportsdata.io/v3/mlb/scores/json/GamesByDate/{$date}")
                    ->json();
          });

          $gameDetails = collect($gamesOnThatDay)->where('GameID', $game->game_id)->first();

          $game = $game->toArray();
          $game['details'] = $this->mlbBuilder->buildGame($gameDetails);

          return $game;

        default:
          # code...
          break;
      }
    });

    return response()->json($mapped, 200);
  }

  public function raw()
  {
    return auth()->user()->watchlist;
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
