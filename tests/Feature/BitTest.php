<?php

namespace Tests\Feature;

use App\Bit;
use App\Category;
use App\User;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BitTest extends TestCase
{
    use WithFaker, RefreshDatabase;
    /** @test */
    public function test_guest_cannot_create_bits()
    {
        foreach (range(1, 10) as $key => $value) {
            create(Category::class);
        }

        /** @var \Illuminate\Foundation\Testing\TestResponse $response */
        $response = $this->graphQL(
            /** @lang GraphQL */
            '
        mutation createBit($title: String, $snippet: String, $tags: [String!]) {
            createBit(title: $title, snippet: $snippet, tags: $tags) {
                id
                title
                snippet
            }
        }
    ',
            [
                'title' => 'Automatic testing proven to reduce stress levels in developers',
                'snippet' => $snippet = $this->faker->sentence(200),
                'tags' => $tags = Category::orderBy('name')->distinct()->limit(3)->get()->pluck('name')
            ]
        );

        // $response->dump();

        $errorExtension = $response->json("errors.*.extensions");

        $this->assertSame(
            [
                [
                    'category' => 'internal'
                ]
            ],
            $errorExtension
        );

        $response->assertJson([
            'errors' => [
                [
                    'extensions' => [
                        'category' => 'internal'
                    ]
                ]
            ]
        ]);
    }
    /** @test */
    public function test_all_bits_created_by_login_user()
    {
        $this->signIn();
        $bits = [];
        foreach (range(1, 10) as $value) {
            $bits[] = create(Bit::class, [
                'user_id' => auth()->id()
            ]);
        }
        /** @var \Illuminate\Foundation\Testing\TestResponse $response */
        $response = $this->graphQL(
            /** @lang GraphQL */
            '{
                bits {
                    data {
                        id
                        title
                    }
                    paginatorInfo{
                        total
                    }
                }
            }'
        );

        $data = array_map(function ($bit) {
            return [
                'id' => $bit['id'],
                'title' => $bit['title']
            ];
        }, $bits);

        $response->assertJson([
            'data' => [
                'bits' => [
                    'data' => $data,
                    'paginatorInfo' => [
                        'total' => count($bits)
                    ]
                ]
            ]
        ]);
    }

    /** @test */
    public function test_tags_are_associated_when_creating_bits()
    {
        $this->signIn();
        foreach (range(1, 10) as $key => $value) {
            create(Category::class);
        }
        $response = $this->graphQL(
            /** @lang GraphQL */
            '
                mutation createBit($title: String, $snippet: String, $tags: [String!]) {
                    createBit(title: $title, snippet: $snippet, tags: $tags) {
                        id
                        title 
                        snippet
                        tags
                    }
                }
            ',
            [
                'title' => $title = 'Automatic testing proven to reduce stress levels in developers',
                'snippet' => $snippet = $this->faker->sentence(200),
                'tags' => $tags = Category::orderBy('name')->distinct()->limit(3)->get()->pluck('name')
            ]
        );

        // $response->dump();
        $response->assertJson([
            'data' => [
                'createBit' => [
                    'id' => 1,
                    'title' => $title,
                    'snippet' => $snippet,
                    'tags' => $tags->all()
                ]
            ]
        ]);
        $response->assertJsonCount(3, 'data.createBit.tags');
    }

    /** @test */
    public function test_edit_bit()
    {
        $user = create(User::class);
        $this->signIn($user);
        foreach (range(1, 5) as $key => $value) {
            create(Category::class);
        }

        $bit = create(Bit::class, [
            'user_id' => $user->id
        ]);
        $bit->tags()->sync([1, 2, 3]);

        $response = $this->graphQL(
            /** @lang GraphQL */
            '
                mutation editBit($id: ID!,$title: String, $snippet: String, $tags: [String!]) {
                    editBit(id: $id, title: $title, snippet: $snippet, tags: $tags) {
                        id
                        title 
                        snippet
                        tags
                    }
                }
            ',
            [
                'id' => $bitId = $bit->id,
                'title' => $title = 'Automatic testing proven to reduce stress levels in developers',
                'snippet' => $snippet = $this->faker->sentence(200),
                'tags' => $tags = Category::orderBy('name')->distinct()->limit(3)->get()->pluck('name')
            ]
        );

        $response->assertJson([
            'data' => [
                'editBit' => [
                    'id' => $bit->id,
                    'title' => $title,
                    'snippet' => $snippet,
                    'tags' => $tags->all()
                ]
            ]
        ]);
        $response->assertJsonCount(3, 'data.editBit.tags');
    }

    /** @test */
    public function test_unauthorised_user_cannot_edit_bit()
    {
        $this->signIn();

        foreach (range(1, 5) as $key => $value) {
            create(Category::class);
        }

        $bit = create(Bit::class, [
            'user_id' => create(User::class)->id
        ]);
        $bit->tags()->sync([1, 2, 3]);

        $response = $this->graphQL(
            /** @lang GraphQL */
            '
                mutation editBit($id: ID!,$title: String, $snippet: String, $tags: [String!]) {
                    editBit(id: $id, title: $title, snippet: $snippet, tags: $tags) {
                        id
                        title 
                        snippet
                        tags
                    }
                }
            ',
            [
                'id' => $bitId = $bit->id,
                'title' => $title = 'Automatic testing proven to reduce stress levels in developers',
                'snippet' => $snippet = $this->faker->sentence(200),
                'tags' => $tags = Category::orderBy('name')->distinct()->limit(3)->get()->pluck('name')
            ]
        );

        // $response->dump();

        $errorExtension = $response->json("errors.*.extensions");

        $this->assertSame(
            [
                [
                    'category' => 'authorization'
                ]
            ],
            $errorExtension
        );

        $response->assertJson([
            'errors' => [
                [
                    'extensions' => [
                        'category' => 'authorization'
                    ]
                ]
            ]
        ]);
    }
}
