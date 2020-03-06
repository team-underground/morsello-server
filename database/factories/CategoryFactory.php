<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Category;
use Faker\Generator as Faker;

$factory->define(Category::class, function (Faker $faker) {
    $categories = [
        "swift",
        "kubernetes",
        "laravel",
        "computerscience",
        "testing",
        "opensource",
        "docker",
        "bash",
        "ubuntu",
        "blockchain",
        "css",
        "angular",
        "flutter",
        "c#",
        "javascript",
        "java",
        "devops",
        "android",
        "rust",
        "vscode",
        "security",
        "linux",
        "machinelearning",
        "git",
        "dotnet",
        "kotlin",
        "ios",
        "c++",
        "graphql",
        "django",
        "dart",
        "go",
        "vim",
        "npm",
        "sql",
        "aws",
        "rails",
        "elixir",
        "react",
        "typescript",
        "python",
        "career",
        "vue",
        "php",
        "ruby",
        "beginners",
        "intermediate",
        "advanched"
    ];
    return [
        'name' => $faker->randomElement($categories)
    ];
});
