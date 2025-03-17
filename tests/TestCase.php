<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    public function signInAsRoot()
    {
        $user = $this->rootUser();
        $this->actingAs($user);

        return $user;
    }

    public function rootUser()
    {
        return $this->create(User::class, [
            'email' => config('app.users.root'),
        ]);
    }

    public function create(string $model, array $attributes = [])
    {
        return $model::factory()->create($attributes);
    }

    public function make(string $model, array $attributes = [])
    {
        return $model::factory()->make($attributes);
    }
}
