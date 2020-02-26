<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Bit;
use Faker\Generator as Faker;

$factory->define(Bit::class, function (Faker $faker) {
    return [
        'user_id' => 1,
        'title' => $faker->sentence(5),
        'snippet' => $faker->sentence(),
    ];
});
