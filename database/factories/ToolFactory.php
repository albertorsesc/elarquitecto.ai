<?php

namespace Database\Factories;

use App\Enums\ToolBusinessModelEnum;
use App\Models\Tool;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tool>
 */
class ToolFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->sentence(3);

        return [
            'uuid' => Str::uuid()->toString(),
            'title' => $title,
            'slug' => Str::slug($title),
            'excerpt' => fake()->paragraph(2),
            'description' => fake()->paragraphs(5, true),
            'business_model' => fake()->randomElement(ToolBusinessModelEnum::cases())->value,
            'featured_image' => fake()->imageUrl(800, 600, 'tech'),
            'gallery' => fake()->optional()->randomElements([
                fake()->imageUrl(800, 600, 'tech'),
                fake()->imageUrl(800, 600, 'tech'),
                fake()->imageUrl(800, 600, 'tech'),
            ], rand(0, 3)),
            'website_url' => fake()->optional()->url(),
            'pricing_url' => fake()->optional()->url(),
            'documentation_url' => fake()->optional()->url(),
            'meta_title' => fake()->optional()->sentence(6),
            'meta_description' => fake()->optional()->paragraph(1),
            'meta_keywords' => fake()->optional()->words(5),
            'structured_data' => null,
            'is_featured' => fake()->boolean(20), // 20% chance of being featured
            'published_at' => fake()->optional(0.8)->dateTimeBetween('-1 year', 'now'), // 80% published
        ];
    }

    /**
     * Indicate that the tool is published.
     */
    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'published_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ]);
    }

    /**
     * Indicate that the tool is a draft.
     */
    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'published_at' => null,
        ]);
    }

    /**
     * Indicate that the tool is featured.
     */
    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
        ]);
    }

    /**
     * Indicate that the tool has a specific business model.
     */
    public function businessModel(ToolBusinessModelEnum $model): static
    {
        return $this->state(fn (array $attributes) => [
            'business_model' => $model->value,
        ]);
    }

    /**
     * Indicate that the tool is free.
     */
    public function free(): static
    {
        return $this->businessModel(ToolBusinessModelEnum::FREE);
    }

    /**
     * Indicate that the tool is freemium.
     */
    public function freemium(): static
    {
        return $this->businessModel(ToolBusinessModelEnum::FREEMIUM);
    }

    /**
     * Indicate that the tool is paid.
     */
    public function paid(): static
    {
        return $this->businessModel(ToolBusinessModelEnum::PAID);
    }

    /**
     * Indicate that the tool is subscription-based.
     */
    public function subscription(): static
    {
        return $this->businessModel(ToolBusinessModelEnum::SUBSCRIPTION);
    }

    /**
     * Indicate that the tool is one-time payment.
     */
    public function oneTime(): static
    {
        return $this->businessModel(ToolBusinessModelEnum::ONE_TIME);
    }

    /**
     * Indicate that the tool is open source.
     */
    public function openSource(): static
    {
        return $this->businessModel(ToolBusinessModelEnum::OPEN_SOURCE);
    }
}
