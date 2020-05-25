<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Game;
use App\Stadium;
use App\Team;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Game::class, function (Faker $faker) {
	$homeTeam = factory(Team::class)->create();
	$awayTeam = factory(Team::class)->create();

	$game = [
    	'GlobalGameID' => $faker->numberBetween(10000, 99999),
    	'GlobalAwayTeamID' => $awayTeam->GlobalTeamID,
    	'GlobalHomeTeamID' => $homeTeam->GlobalTeamID,
    	'Date' => now(),
    	'Status' => 'Scheduled',
    	'StadiumID' => function () {
    		return factory(Stadium::class)->create()->StadiumID;
    	},
    	'GameType' => 'ExampleGameType',
    ];

    $extras = [
    	'DateTime' => now(),
    	'HomeTeam' => $homeTeam->Name,
    	'AwayTeam' => $awayTeam->Name,
    	'HomeRotationNumber' => null,
    	'AwayRotationNumber' => null,
    	'HomeScore' => null,
    	'AwayScore' => null,
    	'HomeTeamMoneyLine' => null,
    	'AwayTeamMoneyLine' => null,
    	'PointSpreadHomeTeamMoneyLine' => null,
    	'PointSpreadAwayTeamMoneyLine' => null,
    	'OverUnder' => null,
    	'StadiumDetails' => null
    ];

    return array_merge($game, [
    	'All' => json_encode(array_merge($game, $extras))
    ]);
});
