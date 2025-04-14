<?php

namespace Database\Seeders;

use App\Enums\CategoryEnum;
use App\Enums\TagEnum;
use App\Enums\PromptCategoryEnum;
use App\Enums\PromptTagEnum;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategoryAndTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing tags and categories to avoid constraint violations
        $this->clearExistingData();
        
        // Create categories from CategoryEnum
        $this->seedMainCategories();
        
        // Create categories and tags for prompts
        $this->seedPromptCategoriesAndTags();
    }
    
    /**
     * Clear existing tags and categories
     */
    private function clearExistingData(): void
    {
        // We need to delete tags first due to foreign key constraints
        Tag::query()->delete();
        Category::query()->delete();
    }
    
    /**
     * Seed main categories and their tags
     */
    private function seedMainCategories(): void
    {
        foreach (CategoryEnum::cases() as $categoryCase) {
            $category = Category::create([
                'name' => $categoryCase->value,
                'slug' => $categoryCase->slug(),
                'description' => $this->getCategoryDescription($categoryCase),
            ]);
            
            // For each category, create its associated tags
            $this->createTagsForCategory($category, $categoryCase->value);
        }
    }
    
    /**
     * Seed prompt categories and their tags
     */
    private function seedPromptCategoriesAndTags(): void
    {
        foreach (PromptCategoryEnum::cases() as $promptCategoryCase) {
            // Check if the category already exists (maps to a main category)
            $categoryName = $promptCategoryCase->value;
            $category = Category::where('name', $categoryName)->first();
            
            // If it doesn't exist, create it
            if (!$category) {
                $category = Category::create([
                    'name' => $categoryName,
                    'slug' => $promptCategoryCase->slug(),
                    'description' => $this->getPromptCategoryDescription($promptCategoryCase),
                ]);
            }
            
            // For each prompt category, create its associated prompt tags
            $this->createPromptTagsForCategory($category, $promptCategoryCase->value);
        }
    }
    
    /**
     * Create tags for a given category
     */
    private function createTagsForCategory(Category $category, string $categoryName): void
    {
        // Get tags for this category using the TagEnum::getByCategory method
        $tagNames = TagEnum::getByCategory($categoryName);
        
        // Create tags for this category
        foreach ($tagNames as $tagName) {
            Tag::create([
                'name' => $tagName,
                'slug' => Str::slug($tagName),
                'category_id' => $category->id,
            ]);
        }
    }
    
    /**
     * Create prompt tags for a given category
     */
    private function createPromptTagsForCategory(Category $category, string $categoryName): void
    {
        // Get prompt tags for this category using PromptTagEnum::getByCategory
        $promptTagNames = PromptTagEnum::getByCategory($categoryName);
        
        // Create tags for this category
        foreach ($promptTagNames as $tagName) {
            // Check if tag already exists (to avoid duplicates)
            $existingTag = Tag::where('name', $tagName)->where('category_id', $category->id)->first();
            
            if (!$existingTag) {
                Tag::create([
                    'name' => $tagName,
                    'slug' => Str::slug($tagName),
                    'category_id' => $category->id,
                ]);
            }
        }
    }
    
    /**
     * Get a descriptive text for each category
     */
    private function getCategoryDescription(CategoryEnum $category): string
    {
        return match($category) {
            CategoryEnum::AI => 'Artificial Intelligence systems and models including language models, image generation, and other AI capabilities',
            CategoryEnum::MACHINE_LEARNING => 'Machine learning concepts, algorithms, and implementations including neural networks and deep learning',
            CategoryEnum::AUTOMATION => 'Tools and techniques for automating repetitive tasks and workflows',
            CategoryEnum::AGENTS => 'AI systems that can act autonomously or semi-autonomously to complete tasks',
            CategoryEnum::CONTENT_CREATION => 'Tools and techniques for creating high-quality written content for various platforms and audiences',
            CategoryEnum::PROGRAMMING => 'Software development, coding solutions, debugging techniques, and code optimization strategies',
        };
    }
    
    /**
     * Get a descriptive text for each prompt category
     */
    private function getPromptCategoryDescription(PromptCategoryEnum $category): string
    {
        return match($category) {
            PromptCategoryEnum::CONTENT_CREATION => 'Pre-made prompts for creating high-quality written content for various platforms and audiences',
            PromptCategoryEnum::PROGRAMMING => 'Pre-made prompts for software development, coding solutions, debugging techniques, and code optimization',
        };
    }
}
