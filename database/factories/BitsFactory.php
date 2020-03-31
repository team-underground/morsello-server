<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Bit;
use Faker\Generator as Faker;

$factory->define(Bit::class, function (Faker $faker) {
    return [
        'user_id' => $faker->randomElement([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]),
        'title' => $faker->sentence(5),
        'snippet' => $faker->sentence(rand(500, 1000)),
    ];
});
