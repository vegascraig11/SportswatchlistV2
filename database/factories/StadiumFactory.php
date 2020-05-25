<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Stadium;
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

$factory->define(Stadium::class, function (Faker $faker) {
	$team = [
        'StadiumID' => $faker->numberBetween(1, 999),
        'StadiumType' => 'Example Stadium Type',
        'Name' => 'Example Stadium Name',
        'City' => 'Example Stadium City',
        'Country' => 'Example Stadium Country',
    ];

    return array_merge($team, ['All' => json_encode($team)]);
});
