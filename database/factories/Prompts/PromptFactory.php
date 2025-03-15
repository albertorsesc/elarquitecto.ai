<?php

namespace Database\Factories\Prompts;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Prompts\Prompt>
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
            'author_id' => User::factory(),
            'name' => $this->faker->name,
            'description' => $this->faker->sentence,
            'content' => $this->faker->paragraph,
        ];
    }
}