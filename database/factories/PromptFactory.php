<?php

namespace Database\Factories;

use App\Data\CanonicalTaxonomy;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Prompt>
 */
class PromptFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $title = fake()->sentence(),
            'slug' => Str::slug($title),
            'excerpt' => fake()->sentence(10),
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
     * Attach a random canonical category and one of its tags after the
     * prompt is persisted. Both are upserted by slug from CanonicalTaxonomy
     * so test data never diverges from the real taxonomy.
     */
    public function withPromptCategory(): self
    {
        return $this->afterCreating(function ($prompt) {
            $tagDef = fake()->randomElement(CanonicalTaxonomy::tags());
            $categorySlug = $tagDef['category'];

            $category = Category::firstOrCreate(
                ['slug' => $categorySlug],
                ['name' => CanonicalTaxonomy::categoryName($categorySlug) ?? Str::headline($categorySlug)]
            );

            $prompt->setCategory($category);

            $tag = Tag::firstOrCreate(
                ['slug' => $tagDef['slug']],
                [
                    'name' => $tagDef['name'],
                    'category_id' => $category->id,
                ]
            );

            $prompt->setTags([$tag->id]);
        });
    }
}
