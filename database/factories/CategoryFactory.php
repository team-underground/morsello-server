<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Category;
use Faker\Generator as Faker;

$factory->define(Category::class, function (Faker $faker) {
    $categories = [
        [
            "name" => "swift",
            "icon" => "devicon-swift-plain"
        ],
        [
            "name" => "laravel",
            "icon" => "devicon-laravel-plain"
        ],
        [
            "name" => "docker",
            "icon" => "devicon-docker-plain"
        ],
        [
            "name" => "ubuntu",
            "icon" => "devicon-ubuntu-plain"
        ],
        [
            "name" => "css",
            "icon" => "devicon-css3-plain"
        ],
        [
            "name" => "angular",
            "icon" => "devicon-angularjs-plain"
        ],
        [
            "name" => "c#",
            "icon" => "devicon-csharp-plain"
        ],
        [
            "name" => "javascript",
            "icon" => "devicon-javascript-plain"
        ],
        [
            "name" => "java",
            "icon" => "devicon-java-plain"
        ],
        [
            "name" => "android",
            "icon" => "devicon-android-plain"
        ],
        [
            "name" => "linux",
            "icon" => "devicon-linux-plain"
        ],
        [
            "name" => "git",
            "icon" => "devicon-git-plain"
        ],
        [
            "name" => "dot-net",
            "icon" => "devicon-dot-net-plain"
        ],
        [
            "name" => "c++",
            "icon" => "devicon-cplusplus-plain"
        ],
        [
            "name" => "django",
            "icon" => "devicon-django-plain"
        ],
        [
            "name" => "go",
            "icon" => "devicon-go-plain"
        ],
        [
            "name" => "vim",
            "icon" => "devicon-vim-plain"
        ],
        [
            "name" => "mysql",
            "icon" => "devicon-mysql-plain"
        ],
        [
            "name" => "rails",
            "icon" => "devicon-rails-plain"
        ],
        [
            "name" => "erlang",
            "icon" => "devicon-erlang-plain"
        ],
        [
            "name" => "react",
            "icon" => "devicon-react-original"
        ],
        [
            "name" => "typescript",
            "icon" => "devicon-typescript-plain"
        ],
        [
            "name" => "python",
            "icon" => "devicon-python-plain"
        ],
        [
            "name" => "vue",
            "icon" => "devicon-vuejs-plain"
        ],
        [
            "name" => "php",
            "icon" => "devicon-php-plain"
        ],
        [
            "name" => "ruby",
            "icon" => "devicon-ruby-plain"
        ]
    ];
    return [
        'name' => $faker->randomElement($categories)
    ];
});
