<?php

namespace Tests\Integration;

use App\Bit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SearchSnippetTest extends TestCase
{
    /** @test */
    public function it_can_search_by_bit_title()
    {
        create(Bit::class, [
            'title' => 'Optimizing Eloquent'
        ]);
        $this->assertCount(1, Bit::search('optimizing')->get());
        $this->assertCount(0, Bit::search('noptimizing')->get());
    }
}
