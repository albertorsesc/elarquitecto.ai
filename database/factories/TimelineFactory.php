<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Timeline>
 */
class TimelineFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'author_id' => User::factory(),
            'title' => $title = $this->faker->sentence,
            'slug' => Str::slug($title),
            'description' => $this->faker->sentence,
            'excerpt' => $this->faker->sentence,
            'content' => $this->faker->paragraph,
        ];
    }
}
