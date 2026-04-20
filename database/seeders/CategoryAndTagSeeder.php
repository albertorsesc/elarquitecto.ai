<?php

namespace Database\Seeders;

use App\Data\CanonicalTaxonomy;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Database\Seeder;

/**
 * Seeds the canonical categories and tags defined in CanonicalTaxonomy.
 *
 * Running this on a fresh DB produces the same state that the
 * NormalizeCategoriesAndTags migration produces on an existing DB.
 */
class CategoryAndTagSeeder extends Seeder
{
    public function run(): void
    {
        // Categories first — tags reference them by FK.
        foreach (CanonicalTaxonomy::categories() as $def) {
            Category::updateOrCreate(
                ['slug' => $def['slug']],
                [
                    'name' => $def['name'],
                    'description' => $def['description'],
                ]
            );
        }

        foreach (CanonicalTaxonomy::tags() as $def) {
            $category = Category::where('slug', $def['category'])->first();

            if (! $category) {
                continue;
            }

            Tag::updateOrCreate(
                ['slug' => $def['slug']],
                [
                    'name' => $def['name'],
                    'category_id' => $category->id,
                ]
            );
        }
    }
}
