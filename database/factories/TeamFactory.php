<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

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

$factory->define(Team::class, function (Faker $faker) {
	$team = [
        'GlobalTeamID' => $faker->numberBetween(100000, 999999),
        'TeamType' => 'ExampleTeamType',
        'Key' => 'ExampleTeamKey',
        'Name' => 'Example Team',
        'City' => 'Example City'
    ];

    $extras = [
        'WikipediaLogoUrl' => null
    ];

    return array_merge($team, [
        'All' => json_encode(array_merge($team, $extras))
    ]);
});
