<?php

namespace Tests\Unit;

use App\Bit;
use App\User;
use Tests\TestCase;
use Illuminate\Database\Eloquent\Collection;

class UserTest extends TestCase
{
    /** @test */
    public function a_user_has_snippets()
    {
        $user = create(User::class);

        $this->assertInstanceOf(Collection::class, $user->bits);
    }

    /** @test */
    function a_user_has_his_own_snippets()
    {
        $this->signIn();
        create(Bit::class, ['user_id' => auth()->id()]);

        $this->assertCount(1, auth()->user()->bits);
    }

    /** @test */
    public function a_user_has_likes()
    {
        $user = create(User::class);

        $this->assertInstanceOf(Collection::class, $user->likes);
    }

    /** @test */
    public function a_user_has_bookmarks()
    {
        $user = create(User::class);

        $this->assertInstanceOf(Collection::class, $user->bookmarks);
    }

    /** @test */
    public function a_user_could_toogle_a_bookmark()
    {
        $userOne = $this->signIn();
        $bit = create(Bit::class);

        $userOne->toggleBookmark($bit);
        $this->assertCount(1, $userOne->bookmarks);

        $userTwo = $this->signIn();
        $userTwo->bookmarks()->sync($bit->id);
        $userTwo->toggleBookmark($bit);
        $this->assertCount(0, $userTwo->bookmarks);
    }

    /** @test */
    public function a_user_could_toogle_a_like()
    {
        $userOne = $this->signIn();
        $bit = create(Bit::class);

        $userOne->toggleLike($bit);
        $this->assertCount(1, $userOne->likes);

        $userTwo = $this->signIn();
        $userTwo->likes()->sync($bit->id);
        $userTwo->toggleLike($bit);
        $this->assertCount(0, $userTwo->likes);
    }
}
