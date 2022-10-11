<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase;

    /**
     * @var bool
     */
    protected bool $seed = true;

    /**
     * @return User
     */
    public function actingAsAuthorizedUser(): User
    {
        // get first user
        $user = User::all()->first();

        // authorize user
        $this->actingAs($user);

        return $user;
    }
}
