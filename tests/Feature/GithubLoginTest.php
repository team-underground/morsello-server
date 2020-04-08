<?php

namespace Tests\Feature;

use Mockery;
use Tests\TestCase;
use Illuminate\Support\Str;
use Laravel\Socialite\Two\User;
use Laravel\Socialite\Contracts\Factory as Socialite;
use Laravel\Socialite\Two\GithubProvider;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GithubLoginTest extends TestCase
{
    public function mockSocialiteFacade($email = 'foo@bar.com', $token = 'foo', $id = 1)
    {
        $socialiteUser = $this->createMock(User::class);
        $socialiteUser->token = $token;
        $socialiteUser->id = $id;
        $socialiteUser->email = $email;

        $provider = $this->createMock(GithubProvider::class);
        $provider->expects($this->any())
            ->method('user')
            ->willReturn($socialiteUser);

        $stub = $this->createMock(Socialite::class);
        $stub->expects($this->any())
            ->method('driver')
            ->willReturn($provider);

        // Replace Socialite Instance with our mock
        $this->app->instance(Socialite::class, $stub);
    }

    /** @test */
    public function it_redirects_to_github()
    {
        $response = $this->call('GET', 'login/github');
        $this->assertStringContainsString('github.com/login/oauth', $response->getTargetUrl());
    }

    /** @test */
    public function it_retrieves_github_request_and_creates_a_new_user()
    {
        // Mock the Facade and return a User Object with the email 'foo@bar.com'
        $this->mockSocialiteFacade('foo@bar.com');

        $response = $this->get('login/github/callback');

        $this->assertStringContainsString('http://localhost:8081/login/github/callback?token=foo&provider=github', $response->getTargetUrl());
        // as the token and provider is passed to front end, please check the socialLogin mutation from the front side
    }
}
