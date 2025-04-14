<?php

namespace Database\Factories;

use App\Enums\PromptCategoryEnum;
use App\Enums\PromptTagEnum;
use App\Models\Category;
use App\Models\Tag;
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

    /**
     * Configure the model factory to use a prompt-specific category.
     */
    public function withPromptCategory(): self
    {
        return $this->afterCreating(function ($prompt) {
            // Get a random prompt category
            $categoryValue = fake()->randomElement(PromptCategoryEnum::values());

            // Find or create the category
            $category = Category::firstOrCreate(
                ['slug' => Str::slug($categoryValue)],
                ['name' => $categoryValue]
            );

            // Assign the category to the prompt
            $prompt->setCategory($category);

            // Get tags for this category
            $tagsForCategory = PromptTagEnum::getByCategory($categoryValue);

            if (count($tagsForCategory) > 0) {
                // Create at least one tag for this category
                $tagValue = fake()->randomElement($tagsForCategory);
                $tag = Tag::firstOrCreate(
                    ['slug' => Str::slug($tagValue)],
                    [
                        'name' => $tagValue,
                        'category_id' => $category->id,
                    ]
                );

                // Assign the tag to the prompt
                $prompt->setTags([$tag->id]);
            }
        });
    }
}
