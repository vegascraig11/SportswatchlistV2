<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('nba/gamesByDate/{date}', 'NBAGamesController@gamesByDate');

Route::get('nhl/gamesByDate/{date}', 'NHLGamesController@gamesByDate');

Route::get('mlb/gamesByDate/{date}', 'MLBGamesController@gamesByDate');

Route::get('ncaab/gamesByDate/{date}', 'NCAABGamesController@gamesByDate');