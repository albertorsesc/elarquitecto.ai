<?php

namespace Tests\Feature;

use App\Data\CanonicalTaxonomy;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Verifies the normalize_categories_and_tags migration against scenarios
 * that actually happen on production — casing drift, tag name with a
 * space, a tag that shares a slug with a canonical one, etc. Each test
 * seeds the messy starting state, runs the migration, and asserts the
 * canonical outcome.
 *
 * Because the migration runs automatically via RefreshDatabase (it's a
 * real migration in the timestamps sequence), we don't re-run it
 * manually. Instead we set up the BAD state AFTER it has run, then
 * re-execute the migration's logic via the seeder (which uses the same
 * CanonicalTaxonomy source) to verify the normalization behavior.
 *
 * For the "messy production" case, we reach into the table after
 * RefreshDatabase and insert dirty rows, then call the same upsert logic
 * again and assert merges / cleanups happen.
 */
class NormalizeTaxonomyMigrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_every_canonical_category_slug_is_pure_ascii_kebab(): void
    {
        foreach (CanonicalTaxonomy::categories() as $def) {
            $this->assertSame(
                $def['slug'],
                Str::slug($def['slug']),
                "Category slug '{$def['slug']}' is not already a valid slug — Str::slug would change it."
            );

            $this->assertMatchesRegularExpression(
                '/^[a-z0-9]+(-[a-z0-9]+)*$/',
                $def['slug'],
                "Category slug '{$def['slug']}' is not pure ASCII kebab-case."
            );
        }
    }

    public function test_every_canonical_tag_slug_is_pure_ascii_kebab(): void
    {
        foreach (CanonicalTaxonomy::tags() as $def) {
            $this->assertMatchesRegularExpression(
                '/^[a-z0-9]+(-[a-z0-9]+)*$/',
                $def['slug'],
                "Tag slug '{$def['slug']}' is not pure ASCII kebab-case."
            );
        }
    }

    public function test_every_canonical_slug_equals_str_slug_of_its_name(): void
    {
        // Invariant that prevents canonical name/slug drift — if you add a
        // taxonomy entry where Str::slug(name) != slug, this test tells you
        // which one is wrong BEFORE a production migration silently corrupts it.
        foreach (CanonicalTaxonomy::categories() as $def) {
            $this->assertSame(
                $def['slug'],
                Str::slug($def['name']),
                "Category '{$def['name']}' (slug {$def['slug']}) drifts from Str::slug — expected slug '".Str::slug($def['name'])."'."
            );
        }

        foreach (CanonicalTaxonomy::tags() as $def) {
            $this->assertSame(
                $def['slug'],
                Str::slug($def['name']),
                "Tag '{$def['name']}' (slug {$def['slug']}) drifts from Str::slug — expected slug '".Str::slug($def['name'])."'."
            );
        }
    }

    public function test_every_canonical_tag_references_an_existing_canonical_category(): void
    {
        $categorySlugs = collect(CanonicalTaxonomy::categories())->pluck('slug')->all();

        foreach (CanonicalTaxonomy::tags() as $def) {
            $this->assertContains(
                $def['category'],
                $categorySlugs,
                "Tag '{$def['slug']}' references unknown category '{$def['category']}'."
            );
        }
    }

    public function test_no_canonical_slug_appears_twice(): void
    {
        $categorySlugs = collect(CanonicalTaxonomy::categories())->pluck('slug');
        $this->assertSame(
            $categorySlugs->count(),
            $categorySlugs->unique()->count(),
            'Duplicate category slug in CanonicalTaxonomy.'
        );

        $tagSlugs = collect(CanonicalTaxonomy::tags())->pluck('slug');
        $this->assertSame(
            $tagSlugs->count(),
            $tagSlugs->unique()->count(),
            'Duplicate tag slug in CanonicalTaxonomy.'
        );
    }

    public function test_migration_seeds_all_canonical_rows_on_fresh_db(): void
    {
        // RefreshDatabase already ran the migration on a fresh DB — just
        // assert every canonical row is present.
        foreach (CanonicalTaxonomy::categories() as $def) {
            $this->assertDatabaseHas('categories', [
                'slug' => $def['slug'],
                'name' => $def['name'],
            ]);
        }

        foreach (CanonicalTaxonomy::tags() as $def) {
            $this->assertDatabaseHas('tags', [
                'slug' => $def['slug'],
                'name' => $def['name'],
            ]);
        }
    }

    public function test_existing_category_name_gets_normalized_to_canonical(): void
    {
        // Simulate pre-migration prod: category with correct slug but dirty name.
        Category::where('slug', 'productividad')->update(['name' => 'productividad']);
        Category::where('slug', 'ai')->update(['name' => 'ai']);

        // Re-run the seeder (same logic as migration) to renormalize.
        $this->seed(\Database\Seeders\CategoryAndTagSeeder::class);

        $this->assertSame('Productividad', Category::where('slug', 'productividad')->first()->name);
        $this->assertSame('AI', Category::where('slug', 'ai')->first()->name);
    }

    public function test_existing_tag_name_gets_normalized_to_canonical(): void
    {
        // Pre-migration dirty state
        Tag::where('slug', 'modelos-mentales')->update(['name' => 'Modelos Mentales']);
        Tag::where('slug', 'movil')->update(['name' => 'movil']);

        $this->seed(\Database\Seeders\CategoryAndTagSeeder::class);

        $this->assertSame('Modelos mentales', Tag::where('slug', 'modelos-mentales')->first()->name);
        $this->assertSame('Móvil', Tag::where('slug', 'movil')->first()->name);
    }

    public function test_tag_with_space_in_slug_is_cleaned_up_and_pivots_migrated(): void
    {
        // Simulate the production "api tutorial" tag with a SPACE in slug.
        $codigo = Category::where('slug', 'codigo')->first();
        $dirty = Tag::create([
            'name' => 'api tutorial',
            'slug' => 'api tutorial',
            'category_id' => $codigo->id,
        ]);

        // Attach the dirty tag to a real content row via the taggables pivot
        // (use an article via raw insert to avoid factory complexity).
        DB::table('taggables')->insert([
            'tag_id' => $dirty->id,
            'taggable_id' => 1,
            'taggable_type' => 'App\\Models\\Article',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Run the canonical upsert + merge via a fresh migration invocation.
        (include database_path('migrations/2026_04_20_154436_normalize_categories_and_tags.php'))->up();

        // Dirty row should be gone — merged into canonical 'api-tutorial'.
        $this->assertDatabaseMissing('tags', ['slug' => 'api tutorial']);

        $canonical = Tag::where('slug', 'api-tutorial')->first();
        $this->assertNotNull($canonical);
        $this->assertSame('API tutorial', $canonical->name);

        // Pivot rows should now reference the canonical tag id.
        $this->assertDatabaseHas('taggables', ['tag_id' => $canonical->id]);
        $this->assertDatabaseMissing('taggables', ['tag_id' => $dirty->id]);
    }

    public function test_orphan_tag_with_non_canonical_name_gets_clean_slug(): void
    {
        // A tag whose name drifted to have weird casing but whose slug is
        // NOT in CanonicalTaxonomy — the migration should still clean the
        // slug to be kebab-case.
        $codigo = Category::where('slug', 'codigo')->first();

        Tag::create([
            'name' => 'Legacy Thing',
            'slug' => 'Legacy Thing',
            'category_id' => $codigo->id,
        ]);

        (include database_path('migrations/2026_04_20_154436_normalize_categories_and_tags.php'))->up();

        $this->assertDatabaseMissing('tags', ['slug' => 'Legacy Thing']);
        $this->assertDatabaseHas('tags', ['slug' => 'legacy-thing']);
    }

    public function test_migration_is_idempotent(): void
    {
        $categoriesBefore = Category::count();
        $tagsBefore = Tag::count();

        // Running the migration logic again should be a no-op on a clean
        // canonical state.
        (include database_path('migrations/2026_04_20_154436_normalize_categories_and_tags.php'))->up();

        $this->assertSame($categoriesBefore, Category::count());
        $this->assertSame($tagsBefore, Tag::count());
    }
}
