<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Like;
use Faker\Generator as Faker;

$factory->define(Like::class, function (Faker $faker) {
    return [
        'user_id' => $faker->randomElement([1, 2, 3, 4, 5, 6, 7]),
        'bit_id' => $faker->randomElement([1, 2, 3, 4, 5, 6, 7])
    ];
});
