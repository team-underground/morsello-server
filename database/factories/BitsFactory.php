<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Bit;
use App\User;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(Bit::class, function (Faker $faker, $attributes = []) {
    return [
        'uuid' => Str::uuid(),
        'user_id' => factory(User::class),
        'title' => $title = $attributes['title'] ?? $faker->sentence(5),
        'slug' => Str::slug($title),
        'snippet' => $faker->sentence(rand(500, 1000))
    ];
});
