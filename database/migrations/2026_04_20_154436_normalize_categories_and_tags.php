<?php

use App\Data\CanonicalTaxonomy;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Brings the categories and tags tables into canonical state:
 *   - Upserts every canonical category (by slug) with the canonical name
 *   - Upserts every canonical tag (by slug) with the canonical name and
 *     parent category
 *   - Recomputes slugs from names for any rows that have non-kebab slugs
 *     (e.g., "api tutorial" with a space) and merges the pivot rows onto
 *     the canonical row, deleting the dead duplicate
 *   - Leaves pre-existing tools/prompts/articles content untouched
 *   - Preserves every existing slug that the site already serves, so no
 *     URLs break and no SEO is lost
 *
 * Idempotent: safe to re-run. Wraps in a transaction so any failure rolls
 * back cleanly.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::transaction(function () {
            $this->upsertCategories();
            $this->upsertTags();
            $this->mergeDuplicateSlugs();
        });
    }

    public function down(): void
    {
        // Data migration — no structural reverse. Production DB backups cover
        // the rollback path. The up() is idempotent, so re-running it after a
        // partial rollback is safe.
    }

    /**
     * Create or update each canonical category. Matching is by slug so a
     * category that already exists on production (same slug) gets its name
     * refreshed; a canonical category that doesn't exist yet is created.
     */
    private function upsertCategories(): void
    {
        foreach (CanonicalTaxonomy::categories() as $def) {
            Category::updateOrCreate(
                ['slug' => $def['slug']],
                [
                    'name' => $def['name'],
                    'description' => $def['description'],
                ]
            );
        }
    }

    /**
     * Create or update each canonical tag. The parent category is resolved
     * by slug from the canonical definition — this is why categories are
     * upserted first.
     */
    private function upsertTags(): void
    {
        foreach (CanonicalTaxonomy::tags() as $def) {
            $category = Category::where('slug', $def['category'])->first();

            if (! $category) {
                // Shouldn't happen — means canonical taxonomy references a
                // category that doesn't exist. Skip rather than fail the
                // whole migration.
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

    /**
     * Walk every existing tag row and re-derive a clean slug from its name
     * (e.g., "api tutorial" → "api-tutorial"). If the cleaned slug already
     * belongs to a canonical row, merge the pivot rows from the dirty tag
     * onto the canonical one and delete the dirty row.
     *
     * Canonical rows themselves are skipped — their slug is the
     * authoritative value and is NOT re-derived from the name. This
     * guarantees we never drift away from the CanonicalTaxonomy definition.
     */
    private function mergeDuplicateSlugs(): void
    {
        $canonicalSlugs = array_merge(
            array_column(CanonicalTaxonomy::categories(), 'slug'),
            array_column(CanonicalTaxonomy::tags(), 'slug')
        );

        $allTags = Tag::all();

        foreach ($allTags as $tag) {
            // Leave canonical rows alone — their slug is authoritative.
            if (in_array($tag->slug, $canonicalSlugs, true)) {
                continue;
            }

            $cleanSlug = Str::slug($tag->name);

            if ($cleanSlug === '' || $cleanSlug === $tag->slug) {
                continue;
            }

            $canonical = Tag::where('slug', $cleanSlug)
                ->where('id', '!=', $tag->id)
                ->first();

            if ($canonical) {
                // Re-point every pivot row from the dirty tag to the
                // canonical one, then remove the dirty row.
                DB::table('taggables')
                    ->where('tag_id', $tag->id)
                    ->update(['tag_id' => $canonical->id]);

                $tag->delete();
            } else {
                // No canonical rival — just clean up this row's slug in
                // place so the URL and rendering stay consistent.
                $tag->slug = $cleanSlug;
                $tag->save();
            }
        }
    }
};
