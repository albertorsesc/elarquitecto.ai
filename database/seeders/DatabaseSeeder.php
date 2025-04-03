<?php

namespace Database\Seeders;

use App\Models\Tag;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Alberto Rosas',
            'email' => config('app.users.root'),
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // add 4 tags related to AI
        Tag::create([
            'name' => 'AI',
        ]);
        Tag::create([
            'name' => 'Machine Learning',
        ]);
        Tag::create([
            'name' => 'Deep Learning',
        ]);
        Tag::create([
            'name' => 'Natural Language Processing',
        ]);

    }
}
