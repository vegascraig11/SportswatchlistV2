<?php

namespace App\Http\Controllers;

use App\Game;
use Illuminate\Http\Request;

class GameController extends Controller
{
    public function index()
    {
        $ids = json_decode(request()->ids);

        return Game::whereIn('GlobalGameID', $ids)->get();
    }
}
