<?php

namespace Tests\Feature;

use App\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    /** @test */
    public function testAllCategories()
    {
        $this->signIn();
        $createdCategories = [];
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
        foreach ($categories as $category) {
            $createdCategories[] = create(Category::class, [
                'name' => $category
            ]);
        }
        /** @var \Illuminate\Foundation\Testing\TestResponse $response */
        $response = $this->graphQL(
            /** @lang GraphQL */
            '
    {
        categories { 
            id
            name  
        }
    }
    '
        );

        $data = collect($createdCategories)->map(function ($category) {
            return [
                'id' => $category->id,
                'name' => $category->name
            ];
        })->all();

        $response->assertJson([
            'data' => [
                'categories' => $data
            ]
        ]);
    }
}
