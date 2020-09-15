<?php

namespace App\Http\Controllers;

use App\Game;
use App\Watchlist;
use Illuminate\Http\Request;

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

    public function destroy(Watchlist $watchlist)
    {
        $watchlist->delete();

        return response()->json([], 204);
    }
}
