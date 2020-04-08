<?php

namespace Tests\Feature;

use App\Bit;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookmarkTest extends TestCase
{
    /** @test */
    public function test_guest_cannot_bookmark_a_snippet()
    {
        $bit = create(Bit::class);

        /** @var \Illuminate\Foundation\Testing\TestResponse $response */
        $response = $this->graphQL(
            /** @lang GraphQL */
            '
                mutation bookmarkBit($bitId: Int) {
                    bookmarkBit(bitId: $bitId) {
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
    public function test_is_bookmarked_data_for_guest_returns_false()
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
                        bookmarks_count
                        isBookmarked
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
                    'bookmarks_count' => 0,
                    'isBookmarked' => false
                ]
            ]
        ]);
    }

    public function test_login_user_can_bookmark_a_snippet()
    {
        $this->signIn();
        $bit = create(Bit::class);

        /** @var \Illuminate\Foundation\Testing\TestResponse $response */
        $response = $this->graphQL(
            /** @lang GraphQL */
            '
                mutation bookmarkBit($bitId: Int) {
                    bookmarkBit(bitId: $bitId) {
                        id
                        title
                        snippet
                        bookmarks_count
                        isBookmarked
                    }
                }
            ',
            [
                'bitId' => $bit->id,
            ]
        );

        $response->assertExactJson([
            'data' => [
                'bookmarkBit' => [
                    'id' => (string) $bit->id,
                    'title' => $bit->title,
                    'snippet' => $bit->snippet,
                    'bookmarks_count' => 1,
                    'isBookmarked' => true
                ]
            ]
        ]);
    }

    /** @test */
    public function test_correct_bookmark_count_returned_after_bookmarking_a_snippet()
    {

        $this->signIn();

        $bit = create(Bit::class);

        $bookmarkableUsers = [];
        foreach (range(1, 3) as $key => $value) {
            $bookmarkableUsers[] = create(User::class);
        }

        $bookmarkableUserIds = array_column($bookmarkableUsers, 'id');

        $bit->bookmarks()->sync($bookmarkableUserIds);

        // before bookmarking the snippet, 
        //check that it has correct no of bookmarks count and the snippet is not bookmarked by the autehnticated user

        /** @var \Illuminate\Foundation\Testing\TestResponse $response */
        $response = $this->graphQL(
            /** @lang GraphQL */
            '
                query bit($slug: String!) {
                    bit(slug: $slug) {
                        id
                        title
                        snippet
                        bookmarks_count
                        isBookmarked
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
                    'bookmarks_count' => 3,
                    'isBookmarked' => false
                ]
            ]
        ]);

        // after auth user has bookmarked the snippet, 
        //check that it has correct no of bookmarks count and it is bookmarked by autehnticated user

        /** @var \Illuminate\Foundation\Testing\TestResponse $response */
        $response = $this->graphQL(
            /** @lang GraphQL */
            '
                mutation bookmarkBit($bitId: Int) {
                    bookmarkBit(bitId: $bitId) {
                        id
                        title
                        snippet
                        bookmarks_count
                        isBookmarked
                    }
                }
            ',
            [
                'bitId' => $bit->id,
            ]
        );

        $response->assertExactJson([
            'data' => [
                'bookmarkBit' => [
                    'id' => (string) $bit->id,
                    'title' => $bit->title,
                    'snippet' => $bit->snippet,
                    'bookmarks_count' => 4,
                    'isBookmarked' => true
                ]
            ]
        ]);
    }

    /** @test */
    public function test_list_all_auth_user_bookmarks()
    {
        $this->signIn();

        foreach (range(1, 10) as $key => $value) {
            create(Bit::class, ['snippet' => 'something']);
        }

        $bookmarkedBits = Bit::select('id', 'title', 'snippet')->limit(5)->get();

        auth()->user()->bookmarks()->sync(array_column($bookmarkedBits->all(), 'id'));

        /** @var \Illuminate\Foundation\Testing\TestResponse $response */
        $response = $this->graphQL(
            /** @lang GraphQL */
            '
                query bookmarks {
                    bookmarks(first: 10) {
                        paginatorInfo {
                            total
                            hasMorePages
                        }
                        data {
                            id
                            title
                            snippet 
                        }
                    }
                }
            '
        );

        $this->assertSame(false, $response->json("data.bookmarks.paginatorInfo.hasMorePages"));
        $this->assertSame(5, $response->json("data.bookmarks.paginatorInfo.total"));

        $response->assertExactJson([
            'data' => [
                'bookmarks' => [
                    'paginatorInfo' => [
                        'total' => 5,
                        'hasMorePages' => false
                    ],
                    'data' => $bookmarkedBits->map(function ($bit) {
                        return [
                            'id' => (string) $bit->id,
                            'snippet' => $bit->snippet,
                            'title' => $bit->title
                        ];
                    })
                ]
            ]
        ]);

        // dd($response);
    }
}
