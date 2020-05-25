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
  public function index()
  {
    return auth()->user()->watchlist;
  }

  public function store()
  {
    $watchlist = auth()->user()->watchlist()->create([
      'game_id' => request('gameId')
    ]);

    return response()->json($watchlist, 201);
  }
}
