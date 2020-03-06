<?php

namespace Tests;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Nuwave\Lighthouse\Testing\MakesGraphQLRequests;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use MakesGraphQLRequests;
    use DatabaseMigrations;

    protected function signIn($user = null)
    {
        $user  = $user ?: create(User::class);
        $this->actingAs($user, 'api');
        return $this;
    }
}
