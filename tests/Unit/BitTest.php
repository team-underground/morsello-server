<?php

namespace Tests\Unit;

use App\Bit;
use App\User;
use App\Category;
use Tests\TestCase;
use Illuminate\Database\Eloquent\Collection;

class BitTest extends TestCase
{
    /** @test */
    public function it_can_find_by_slug()
    {
        create(Bit::class, ['slug' => 'foo']);

        $this->assertInstanceOf(Bit::class, Bit::whereSlug('foo')->first());
    }

    /** @test */
    public function it_generates_a_slug_when_saving()
    {
        $snippet = create(Bit::class, ['title' => 'Help with eloquent']);
        $this->assertEquals('help-with-eloquent', $snippet->slug);
    }

    /** @test */
    public function it_generates_a_unique_slug_when_valid_url_characters_provided()
    {
        $snippetOne = create(Bit::class, ['title' => 'Help with eloquent']);
        $snippetTwo = create(Bit::class, ['title' => 'Help with eloquent']);

        $this->assertEquals('help-with-eloquent-1', $snippetTwo->slug);
    }

    /** @test */
    public function it_belongs_to_an_user()
    {
        $bit = create(Bit::class);

        $this->assertInstanceOf(User::class, $bit->user);
    }

    /** @test */
    public function a_snippet_has_likes()
    {
        $bit = create(Bit::class);

        $this->assertInstanceOf(Collection::class, $bit->likes);
    }

    /** @test */
    public function a_snippet_is_liked()
    {
        $this->signIn();
        $bit = create(Bit::class);
        $bit->likes()->sync(auth()->id());
        $this->assertTrue($bit->isLiked());
    }

    /** @test */
    public function a_snippet_has_bookmarks()
    {
        $bit = create(Bit::class);

        $this->assertInstanceOf(Collection::class, $bit->bookmarks);
    }

    /** @test */
    public function a_snippet_is_bookmarked()
    {
        $this->signIn();
        $bit = create(Bit::class);
        $bit->bookmarks()->sync(auth()->id());
        $this->assertTrue($bit->isBookmarked());
    }

    /** @test */
    public function a_snippet_has_tags()
    {
        $bit = create(Bit::class);

        $this->assertInstanceOf(Collection::class, $bit->tags);
    }

    /** @test */
    public function associated_tags_of_a_snippet()
    {
        $bit = create(Bit::class);
        $categoryOne = create(Category::class, ['name' => 'laravel']);
        $categoryTwo = create(Category::class, ['name' => 'vue']);

        $bit->tags()->sync([$categoryOne->id, $categoryTwo->id]);
        $this->assertEquals(['laravel', 'vue'], $bit->associatedTags()->all());
    }
}
