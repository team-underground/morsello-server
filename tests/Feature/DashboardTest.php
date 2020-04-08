<?php

namespace Tests\Feature;

use App\Bit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    /** @test */
    public function it_returns_a_valid_dashboard_data()
    {
        $user = $this->signIn();
        $bitMarch = create(Bit::class, ['created_at' => '2020-03-10', 'user_id' => $user]);
        $bitApril = create(Bit::class, ['created_at' => '2020-04-10', 'user_id' => $user]);

        $user->toggleBookmark($bitMarch);

        $response = $this->json('get', '/api/dashboard');

        $response->assertExactJson([
            'data' => [
                0, 0, "1", "1", 0, 0, 0, 0, 0, 0, 0, 0
            ],
            'snippets_count' => 2,
            'bookmarks_count' => 1
        ]);
    }
}
