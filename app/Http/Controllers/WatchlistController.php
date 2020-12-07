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
        $settings = [
            "score_gap" => 0,
            "total_score" => 0,
            "start" => false,
            "end" => false,
            "first_half_end" => false,
            "second_half_start" => false,
        ];

        $watchlist = auth()->user()->watchlist()->create([
          'game_id' => request('gameId'),
          'settings' => json_encode($settings),
        ]);

        return response()->json($watchlist, 201);
    }

    public function destroy(Watchlist $watchlist)
    {
        $watchlist->delete();

        return response()->json([], 204);
    }
}
