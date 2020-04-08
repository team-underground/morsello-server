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

        $this->assertSame(
            [
                'Unauthenticated.'
            ],
            $response->json("errors.*.debugMessage")
        );

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
    function it_requires_a_title()
    {
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
                'title' => $title = null,
                'snippet' => $snippet = $this->faker->sentence(200),
                'tags' => $tags = ['angular', 'swift', 'vue']
            ]
        );

        $this->assertSame(
            [
                'The title field is required.'
            ],
            $response->json("errors.*.extensions.validation.title.*")
        );
    }

    /** @test */
    function it_requires_a_snippet()
    {
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
                'title' => $title = $this->faker->title(),
                'snippet' => $snippet = null,
                'tags' => $tags = ['angular', 'swift', 'vue']
            ]
        );

        $this->assertSame(
            [
                'The snippet field is required.'
            ],
            $response->json("errors.*.extensions.validation.snippet.*")
        );
    }

    /** @test */
    function it_is_required_to_associate_minimum_one_tag()
    {
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
                'title' => $title = $this->faker->title(),
                'snippet' => $snippet = $this->faker->sentence(5),
                'tags' => $tags = []
            ]
        );

        $this->assertSame(
            [
                'The tags field is required.'
            ],
            $response->json("errors.*.extensions.validation.tags.*")
        );
    }

    /** @test */
    public function test_tags_are_associated_when_creating_bits()
    {
        $this->signIn();
        create(Category::class, ['name' => 'angular']);
        create(Category::class, ['name' => 'swift']);
        create(Category::class, ['name' => 'vue']);

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
                'tags' => $tags = ['angular', 'swift', 'vue']
            ]
        );

        $response->assertJson([
            'data' => [
                'createBit' => [
                    'id' => 1,
                    'title' => $title,
                    'snippet' => $snippet,
                    'tags' => $tags
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

        create(Category::class, ['name' => 'angular']);
        create(Category::class, ['name' => 'swift']);
        create(Category::class, ['name' => 'vue']);

        create(Category::class, ['name' => 'java']);
        create(Category::class, ['name' => 'python']);

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
                'tags' => $tags = ['angular', 'python']
            ]
        );

        $response->assertJson([
            'data' => [
                'editBit' => [
                    'id' => $bit->id,
                    'title' => $title,
                    'snippet' => $snippet,
                    'tags' => $tags
                ]
            ]
        ]);
        $response->assertJsonCount(2, 'data.editBit.tags');
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

        $this->assertSame(
            [
                'You are not authorized to access editBit'
            ],
            $response->json("errors.*.message")
        );

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

    /** @test */
    public function test_top_snippets()
    {
        $user = $this->signIn();
        $userOne = $this->signIn();
        $bitOne = create(Bit::class, ['title' => 'Bit 1']);
        $bitTwo = create(Bit::class, ['title' => 'Bit 2']);
        $bitThree = create(Bit::class, ['title' => 'Bit 3']);

        $user->toggleLike($bitOne);
        $userOne->toggleLike($bitOne);

        $user->toggleLike($bitTwo);

        $response = $this->graphQL(
            /** @lang GraphQL */
            '
                query topSnippets {
                    topSnippets {
                        id
                        title   
                        likes_count
                    } 
                }
            '
        );

        $response->assertExactJson([
            'data' => [
                'topSnippets' => [
                    [
                        "id" => "1",
                        "title" => "Bit 1",
                        "likes_count" => 2
                    ],
                    [
                        "id" => "2",
                        "title" => "Bit 2",
                        "likes_count" => 1
                    ],
                    [
                        "id" => "3",
                        "title" => "Bit 3",
                        "likes_count" => 0
                    ]
                ]
            ]
        ]);
    }
}
