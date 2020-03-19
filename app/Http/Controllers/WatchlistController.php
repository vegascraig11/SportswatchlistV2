<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Promise;
use GuzzleHttp\HandlerStack;
use Illuminate\Http\Request;
use Brightfish\CachingGuzzle\Middleware;

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
      'headers' => [
        'Ocp-Apim-Subscription-Key' => 'ead3c888cb1447e6b50d2a2e204ed2bf'
      ]
    ]);
  }

  public function index()
  {
    $games = auth()->user()->watchlist;

    $requests = [];

    $games->each(function ($game) use (&$requests) {
      $requests[$game->game_id] = $this->client->get("nba/pbp/json/PlayByPlay/{$game->game_id}", ['cache' => false]);
    });

    $responses = Promise\settle($requests)->wait();

    $mapped = $games->map(function ($game) use ($responses) {
      $gameArray = $game->toArray();

      $gameArray['details'] = json_decode($responses[$gameArray['game_id']]['value']->getBody()->getContents());

      return $gameArray;
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
