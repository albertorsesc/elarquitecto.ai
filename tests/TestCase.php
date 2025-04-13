<?php

namespace Tests;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    public function signInAsRoot()
    {
        $user = User::create([
            'name' => 'Root User',
            'email' => config('auth.roles.r007'),
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        $this->actingAs($user);
    }

    public function create(string $class, array $attributes = []): Model
    {
        return $class::factory()->create($attributes);
    }

    public function make(string $class, array $attributes = []): Model
    {
        return $class::factory()->make($attributes);
    }
}
