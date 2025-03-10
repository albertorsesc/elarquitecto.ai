<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Tag;
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
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Alberto Rosas',
            'email' => 'alberto@email.com',
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