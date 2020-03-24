<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Session;
use Faker\Generator as Faker;

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

$factory->define(Session::class, function (Faker $faker) {
    return [
        'visitor' => $faker->randomNumber(),
        'client' => $faker->randomElement(["Browser", "Unity"]),
        'platform' => $faker->randomElement(["OSX", "Windows", "Android", "iPhone"]),
        'origin' => $faker->country,
    ];
});
