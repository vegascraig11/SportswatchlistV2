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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('nba/gamesByDate/{date}', 'NBAGamesController@gamesByDate');
Route::get('nhl/gamesByDate/{date}', 'NHLGamesController@gamesByDate');
Route::get('mlb/gamesByDate/{date}', 'MLBGamesController@gamesByDate');
Route::get('ncaab/gamesByDate/{date}', 'NCAABGamesController@gamesByDate');
Route::get('ncaaf/gamesByDate/{date}', 'NCAAFGamesController@gamesByDate');
Route::get('nfl/gamesByDate/{date}', 'NFLGamesController@gamesByDate');

Route::get('games', 'GameController@index');
Route::get('games/{gameId}', 'GameController@show');
Route::get('banners', 'BannerController@index');

Route::post('subscriptions', 'SubscriptionsController@store');

Route::post('resend', 'UsersController@resendVerificationEmail');
Route::get('verify-email', 'UsersController@verifyEmail')->name('verification.verify');

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('watchlist', 'WatchlistController@index');
    Route::get('watchlist/raw', 'WatchlistController@raw');
    Route::post('watchlist', 'WatchlistController@store');
    Route::delete('watchlist/{watchlist}', 'WatchlistController@destroy');
    Route::patch('/user/watchlist/{gameId}', 'WatchlistController@update');

    Route::post('banners', 'BannerController@store');
    Route::delete('banners/{banner}', 'BannerController@destroy');

    Route::get('/users', 'UsersController@index');

    Route::post('/export', 'ExportController@index');

    Route::get('/keys', 'AdminController@keys');
    Route::post('/keys', 'AdminController@updateKeys');
});


Route::get('{any}', function () {
  return response()->json([], 404);
});
