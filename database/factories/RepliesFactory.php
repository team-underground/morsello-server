<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Reply;
use Faker\Generator as Faker;

$factory->define(Reply::class, function (Faker $faker) {
    return [
        'user_id' => 1,
        'bit_id' => $faker->randomElement([1, 2, 3, 4]),
        'reply' => $faker->sentence()
    ];
});
