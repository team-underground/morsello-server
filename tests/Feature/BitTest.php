<?php

namespace Tests\Feature;

use App\Bit;
use App\User;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BitTest extends TestCase
{
    /** @test */
    // public function test_guest_cannot_create_bits()
    // {
    //     /** @var \Illuminate\Foundation\Testing\TestResponse $response */
    //     $response = $this->graphQL(
    //         /** @lang GraphQL */
    //         '
    //     mutation createBit($title: String, $snippet: String) {
    //         createBit(title: $title, snippet: $snippet,) {
    //             id
    //         }
    //     }
    // ',
    //         [
    //             'title' => 'Automatic testing proven to reduce stress levels in developers',
    //             'snippet' => 'asdfasdhjgasjdhasghdghj'
    //         ]
    //     );

    //     dd($response);
    // }
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
            '
    {
        bits {
            data {
                id
                title
            }
            paginatorInfo{
                total
            }
        }
    }
    '
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
}
