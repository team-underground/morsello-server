<?php

namespace Tests\Feature;

use App\Bit;
use App\User;
use App\Category;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryTest extends TestCase
{
    /** @test */
    public function testAllCategories()
    {
        $createdCategories = [];

        foreach (range(1, 10) as $category) {
            $createdCategories[] = create(Category::class);
        }

        /** @var \Illuminate\Foundation\Testing\TestResponse $response */
        $response = $this->graphQL(
            /** @lang GraphQL */
            '
                {
                    categories { 
                        id
                        name  
                        icon
                    }
                }
            '
        );

        $data = collect($createdCategories)->map(function ($category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
                'icon' => $category->icon
            ];
        })->all();

        $response->assertJson([
            'data' => [
                'categories' => $data
            ]
        ]);
    }

    /** @test */
    public function testCategoryWiseSnippetsAreReturned()
    {
        $category = create(Category::class);

        $formattedBits = [];

        foreach (range(1, 5) as $key => $value) {
            $bit = create(Bit::class, ['snippet' => 'something']);
            $bit->tags()->sync($category->id);
            $formattedBits[] = [
                'title' => $bit->title,
                'snippet' => $bit->snippet,
                'tags' => [$category->name]
            ];
        }

        /** @var \Illuminate\Foundation\Testing\TestResponse $response */
        $response = $this->graphQL(
            /** @lang GraphQL */
            '
                query categoryWiseSnippet($name: String) {
                    category(name: $name) {
                        id
                        name 
                        icon 
                        bits(first: 10) {
                            paginatorInfo {
                                total
                                hasMorePages
                            }
                            data {
                                title
                                snippet
                                tags
                            }
                        }
                    }
                }
            ',
            [
                'name' => $category->name
            ]
        );

        $response->assertExactJson([
            'data' => [
                'category' => [
                    'id' => (string) $category->id,
                    'name' => $category->name,
                    'icon' => $category->icon,
                    'bits' => [
                        'paginatorInfo' => [
                            'total' => 5,
                            'hasMorePages' => false
                        ],
                        'data' => $formattedBits
                    ]
                ]
            ]
        ]);

        $this->assertSame(false, $response->json("data.category.bits.paginatorInfo.hasMorePages"));
        $this->assertSame(5, $response->json("data.category.bits.paginatorInfo.total"));
    }
}
