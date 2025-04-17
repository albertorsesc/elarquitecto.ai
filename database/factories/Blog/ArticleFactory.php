<?php

namespace Database\Factories\Blog;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Blog\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'uuid' => fake()->uuid(),
            'title' => $title = fake()->sentence(),
            'body' => fake()->paragraph(),
            'slug' => Str::slug($title),
            'author_id' => User::factory(),
            'published_at' => null,
            'is_pinned' => false,
            'is_featured' => false,
            'view_count' => 0,
            'original_url' => null,
        ];
    }
}
