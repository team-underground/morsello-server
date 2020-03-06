<?php

use App\Category;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
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

        foreach ($categories as $key => $category) {
            Category::create([
                'name' => $category
            ]);
        }
    }
}
