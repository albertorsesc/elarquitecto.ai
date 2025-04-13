<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Prompt>
 */
class PromptFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $title = fake()->sentence(),
            'slug' => Str::slug($title),
            'excerpt' => fake()->paragraph(),
            'content' => fake()->paragraph(),
            'published_at' => fake()->dateTimeBetween('-1 year', 'now'),
            'word_count' => fake()->numberBetween(100, 1000),
            'target_models' => fake()->randomElements(
                collect(config('models.models'))->flatten()->toArray(), 
                fake()->numberBetween(1, 3)
            ),
        ];
    }
}
