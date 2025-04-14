<?php

namespace Database\Factories;

use App\Enums\CategoryEnum;
use App\Enums\TagEnum;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tag>
 */
class TagFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Generate a unique name by appending a random suffix
        $baseName = fake()->randomElement(TagEnum::values());
        $name = $baseName . ' ' . Str::random(5);
        $slug = Str::slug($name);
        
        // Get or create the category for this tag
        $categoryName = $this->getCategoryForTag($baseName);
        $category = Category::firstOrCreate(
            ['name' => $categoryName],
            ['slug' => Str::slug($categoryName), 'description' => fake()->sentence()]
        );
        
        return [
            'name' => $name,
            'slug' => $slug,
            'category_id' => $category->id,
        ];
    }
    
    /**
     * Get the category name for a given tag.
     */
    private function getCategoryForTag(string $tagName): string
    {
        // Default category if not found
        $categoryName = CategoryEnum::AI->value;
        
        // Check which category the tag belongs to
        if (in_array($tagName, TagEnum::getByCategory(CategoryEnum::AI->value))) {
            $categoryName = CategoryEnum::AI->value;
        } elseif (in_array($tagName, TagEnum::getByCategory(CategoryEnum::AGENTS->value))) {
            $categoryName = CategoryEnum::AGENTS->value;
        } elseif (in_array($tagName, TagEnum::getByCategory(CategoryEnum::MACHINE_LEARNING->value))) {
            $categoryName = CategoryEnum::MACHINE_LEARNING->value;
        } elseif (in_array($tagName, TagEnum::getByCategory(CategoryEnum::AUTOMATION->value))) {
            $categoryName = CategoryEnum::AUTOMATION->value;
        }
        
        return $categoryName;
    }
}
