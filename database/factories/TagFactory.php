<?php

namespace Database\Factories;

use App\Data\CanonicalTaxonomy;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tag>
 */
class TagFactory extends Factory
{
    public function definition(): array
    {
        // Seeds draw from the canonical taxonomy so test-generated tags
        // always land in the same Spanish-first space the site uses.
        $pick = fake()->randomElement(CanonicalTaxonomy::tags());

        $suffix = Str::random(5);
        $name = $pick['name'].' '.$suffix;
        $slug = Str::slug($name);

        $category = Category::firstOrCreate(
            ['slug' => $pick['category']],
            ['name' => CanonicalTaxonomy::categoryName($pick['category']) ?? Str::headline($pick['category'])]
        );

        return [
            'name' => $name,
            'slug' => $slug,
            'category_id' => $category->id,
        ];
    }
}
