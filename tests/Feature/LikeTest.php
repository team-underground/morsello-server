<?php

namespace Tests\Feature;

use App\Bit;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LikeTest extends TestCase
{
    /** @test */
    public function test_guest_cannot_like_a_snippet()
    {
        $bit = create(Bit::class);

        /** @var \Illuminate\Foundation\Testing\TestResponse $response */
        $response = $this->graphQL(
            /** @lang GraphQL */
            '
                mutation likeBit($bitId: Int) {
                    likeBit(bitId: $bitId) {
                        id
                        title
                        snippet
                    }
                }
            ',
            [
                'bitId' => $bit->id,
            ]
        );

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
    public function test_is_liked_data_for_guest_returns_false()
    {
        $bit = create(Bit::class);

        /** @var \Illuminate\Foundation\Testing\TestResponse $response */
        $response = $this->graphQL(
            /** @lang GraphQL */
            '
                query bit($slug: String!) {
                    bit(slug: $slug) {
                        id
                        title
                        snippet
                        likes_count
                        isLiked
                    }
                }
            ',
            [
                'slug' => $bit->slug,
            ]
        );

        $response->assertExactJson([
            'data' => [
                'bit' => [
                    'id' => (string) $bit->id,
                    'title' => $bit->title,
                    'snippet' => $bit->snippet,
                    'likes_count' => 0,
                    'isLiked' => false
                ]
            ]
        ]);
    }

    public function test_login_user_can_like_a_snippet()
    {
        $this->signIn();
        $bit = create(Bit::class);

        /** @var \Illuminate\Foundation\Testing\TestResponse $response */
        $response = $this->graphQL(
            /** @lang GraphQL */
            '
                mutation likeBit($bitId: Int) {
                    likeBit(bitId: $bitId) {
                        id
                        title
                        snippet
                        likes_count
                        isLiked
                    }
                }
            ',
            [
                'bitId' => $bit->id,
            ]
        );

        $response->assertExactJson([
            'data' => [
                'likeBit' => [
                    'id' => (string) $bit->id,
                    'title' => $bit->title,
                    'snippet' => $bit->snippet,
                    'likes_count' => 1,
                    'isLiked' => true
                ]
            ]
        ]);
    }

    /** @test */
    public function test_correct_like_count_returned_after_liking_a_snippet()
    {

        $this->signIn();

        $bit = create(Bit::class);

        $likableUsers = [];
        foreach (range(1, 3) as $key => $value) {
            $likableUsers[] = create(User::class);
        }

        $likableUserIds = array_column($likableUsers, 'id');

        $bit->likes()->sync($likableUserIds);

        // before liking the snippet, 
        //check that it has correct no of likes count and the snippet is not liked by the autehnticated user

        /** @var \Illuminate\Foundation\Testing\TestResponse $response */
        $response = $this->graphQL(
            /** @lang GraphQL */
            '
                query bit($slug: String!) {
                    bit(slug: $slug) {
                        id
                        title
                        snippet
                        likes_count
                        isLiked
                    }
                }
            ',
            [
                'slug' => $bit->slug,
            ]
        );

        $response->assertExactJson([
            'data' => [
                'bit' => [
                    'id' => (string) $bit->id,
                    'title' => $bit->title,
                    'snippet' => $bit->snippet,
                    'likes_count' => 3,
                    'isLiked' => false
                ]
            ]
        ]);

        // after auth user has liked the snippet, 
        //check that it has correct no of likes count and it is liked by autehnticated user

        /** @var \Illuminate\Foundation\Testing\TestResponse $response */
        $response = $this->graphQL(
            /** @lang GraphQL */
            '
                mutation likeBit($bitId: Int) {
                    likeBit(bitId: $bitId) {
                        id
                        title
                        snippet
                        isLiked
                        likes_count
                    }
                }
            ',
            [
                'bitId' => $bit->id,
            ]
        );

        $response->assertExactJson([
            'data' => [
                'likeBit' => [
                    'id' => (string) $bit->id,
                    'title' => $bit->title,
                    'snippet' => $bit->snippet,
                    'likes_count' => 4,
                    'isLiked' => true
                ]
            ]
        ]);
    }
}
