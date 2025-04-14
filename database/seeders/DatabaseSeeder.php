<?php

namespace Database\Seeders;

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
        // User::factory(10)->create();

        User::create([
            'name' => 'Alberto Rosas',
            'email' => 'alberto@email.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        
        // Seed categories and tags
        $this->call(CategoryAndTagSeeder::class);
    }
}
