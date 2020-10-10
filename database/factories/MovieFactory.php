<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Movie;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Movie::class, function (Faker $faker) {
    return [
        'title' => Str::random(10),
        'backdrop_path' => Str::random(50),
        'poster_path' => Str::random(50),
        'budget' => $faker->numberBetween($min = 100000, $max = 999999999),
        'overview' => $faker->paragraph($nbSentences = 3, $variableNbSentences = true),
        'popularity' => $faker->randomFloat($nbMaxDecimals = 1, $min = 0, $max = 10),
        'release_date' => $faker->date($format = 'Y-m-d', $max = 'now'),
        'revenue' => $faker->numberBetween($min = 100000, $max = 999999999),
        'runtime' => $faker->numberBetween($min = 70, $max = 190),
        'status' => Str::random(5),
        'vote_average' => $faker->randomFloat($nbMaxDecimals = 1, $min = 0, $max = 10),
        'vote_count' => $faker->numberBetween($min = 10, $max = 99999),
    ];
});
