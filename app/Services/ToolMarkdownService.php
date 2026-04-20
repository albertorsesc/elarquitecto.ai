<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Media;
use App\Models\Tag;
use App\Models\Tool;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Spatie\YamlFrontMatter\YamlFrontMatter;

/**
 * Parses markdown files in storage/tools/*.md and upserts them into the
 * tools table. Mirrors NewsletterService for the same markdown+scan pattern
 * Alberto already understands from the newsletter system.
 *
 * Idempotent: skips files whose content hash matches the last processed
 * hash stored in the tool's research_data.file_hash field.
 */
class ToolMarkdownService
{
    private string $toolsPath;

    public function __construct()
    {
        $this->toolsPath = storage_path('tools');
    }

    public function getToolsPath(): string
    {
        return $this->toolsPath;
    }

    /**
     * Scan storage/tools/ for markdown files and sync each to the database.
     * Returns an array of counters for the caller to report.
     */
    public function scanForTools(): array
    {
        $counters = ['processed' => 0, 'skipped' => 0, 'failed' => 0];
        $path = $this->getToolsPath();

        if (! File::exists($path)) {
            File::makeDirectory($path, 0755, true);

            return $counters;
        }

        $markdownFiles = collect(File::files($path))
            ->filter(fn ($file) => $file->getExtension() === 'md');

        foreach ($markdownFiles as $file) {
            try {
                $result = $this->processToolFile($file->getRealPath());

                if ($result === 'processed') {
                    $counters['processed']++;
                } elseif ($result === 'skipped') {
                    $counters['skipped']++;
                }
            } catch (\Throwable $e) {
                $counters['failed']++;
                \Log::error('Failed to process tool markdown file', [
                    'file' => $file->getRealPath(),
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return $counters;
    }

    /**
     * Parse one markdown file and upsert the corresponding tool.
     * Returns "processed", "skipped", or throws on invalid input.
     *
     * Wrapped in a DB transaction so any partial failure (bad taxonomy,
     * image copy error, etc.) rolls back the tool row along with it,
     * keeping the DB in sync with what was actually processed.
     */
    public function processToolFile(string $filePath): string
    {
        $content = File::get($filePath);
        $fileHash = md5($content);

        $document = YamlFrontMatter::parse($content);
        $frontmatter = $document->matter();
        $body = $document->body();

        if (empty($frontmatter['title']) || empty($frontmatter['slug'])) {
            throw new \RuntimeException("Tool file {$filePath} is missing required 'title' or 'slug' in frontmatter.");
        }

        $slug = $frontmatter['slug'];
        $existing = Tool::where('slug', $slug)->first();

        // Idempotency: skip if file content unchanged since last scan.
        if ($existing && data_get($existing->research_data, 'file_hash') === $fileHash) {
            return 'skipped';
        }

        // Validate taxonomy BEFORE any DB writes so error messages are clear
        // and we never create a partial tool row on a typo.
        $this->validateTaxonomy($frontmatter, $filePath);

        $data = $this->buildToolData($frontmatter, $body, $fileHash);

        DB::transaction(function () use ($existing, $data, $frontmatter, $slug) {
            $tool = $existing ?: new Tool;
            $tool->fill($data);
            $tool->save();

            $this->syncTaxonomy($tool, $frontmatter);
            $this->syncFeaturedImage($tool, $frontmatter, $slug);
        });

        return 'processed';
    }

    /**
     * Reject unknown category/tag slugs before touching the DB. The site's
     * taxonomy is enum-driven (CategoryEnum, TagEnum) and seeded — ad-hoc
     * slugs would fragment navigation and SEO.
     */
    private function validateTaxonomy(array $frontmatter, string $filePath): void
    {
        $categorySlugs = collect($frontmatter['categories'] ?? [])
            ->filter()
            ->map(fn ($slug) => Str::slug($slug))
            ->values();

        $tagSlugs = collect($frontmatter['tags'] ?? [])
            ->filter()
            ->map(fn ($slug) => Str::slug($slug))
            ->values();

        $validCategorySlugs = Category::pluck('slug')->all();
        $validTagSlugs = Tag::pluck('slug')->all();

        $unknownCategories = $categorySlugs->diff($validCategorySlugs);
        if ($unknownCategories->isNotEmpty()) {
            throw new \RuntimeException(sprintf(
                'Unknown category slug(s) in %s: [%s]. Valid options: %s',
                basename($filePath),
                $unknownCategories->implode(', '),
                collect($validCategorySlugs)->implode(', ')
            ));
        }

        $unknownTags = $tagSlugs->diff($validTagSlugs);
        if ($unknownTags->isNotEmpty()) {
            throw new \RuntimeException(sprintf(
                'Unknown tag slug(s) in %s: [%s]. Valid options: %s',
                basename($filePath),
                $unknownTags->implode(', '),
                collect($validTagSlugs)->implode(', ')
            ));
        }
    }

    /**
     * Build the tool attributes array from frontmatter and body.
     */
    private function buildToolData(array $frontmatter, string $body, string $fileHash): array
    {
        $affiliate = $this->normalizeAffiliate($frontmatter['affiliate'] ?? null);
        $research = array_merge(
            $this->normalizeResearch($frontmatter['research'] ?? null),
            ['file_hash' => $fileHash]
        );

        return [
            'title' => $frontmatter['title'],
            'slug' => $frontmatter['slug'],
            'excerpt' => $frontmatter['excerpt'] ?? null,
            'description' => trim($body),
            'business_model' => $frontmatter['business_model'] ?? 'free',
            'website_url' => $frontmatter['website_url'] ?? null,
            'pricing_url' => $frontmatter['pricing_url'] ?? null,
            'documentation_url' => $frontmatter['documentation_url'] ?? null,
            'affiliate_data' => $affiliate,
            'research_data' => $research,
            'meta_title' => $frontmatter['meta_title'] ?? null,
            'meta_description' => $frontmatter['meta_description'] ?? null,
            'meta_keywords' => $frontmatter['meta_keywords'] ?? [],
            'is_featured' => (bool) ($frontmatter['is_featured'] ?? false),
            'published_at' => $this->parsePublishedAt($frontmatter),
        ];
    }

    private function normalizeAffiliate($value): ?array
    {
        if (! is_array($value) || empty($value['url'])) {
            return null;
        }

        return array_filter([
            'url' => $value['url'],
            'program' => $value['program'] ?? null,
            'commission' => $value['commission'] ?? null,
            'signup_url' => $value['signup_url'] ?? null,
            'notes' => $value['notes'] ?? null,
        ], fn ($v) => $v !== null && $v !== '');
    }

    private function normalizeResearch($value): array
    {
        if (! is_array($value)) {
            return [];
        }

        return array_filter([
            'score' => $value['score'] ?? null,
            'validated_at' => $value['validated_at'] ?? null,
            'sources' => $value['sources'] ?? [],
            'alternatives_considered' => $value['alternatives_considered'] ?? [],
            'why_include' => $value['why_include'] ?? null,
        ], fn ($v) => $v !== null && $v !== '' && $v !== []);
    }

    private function parsePublishedAt(array $frontmatter): ?Carbon
    {
        $raw = $frontmatter['published_at'] ?? null;

        if (empty($raw)) {
            return null;
        }

        $timezone = $frontmatter['timezone'] ?? 'America/Tijuana';

        try {
            return Carbon::parse($raw, $timezone)->utc();
        } catch (\Exception $e) {
            \Log::warning('Invalid published_at in tool frontmatter; leaving as draft', [
                'slug' => $frontmatter['slug'] ?? null,
                'value' => $raw,
            ]);

            return null;
        }
    }

    /**
     * Attach existing categories and tags. Validation already happened
     * upfront so we can assume all slugs resolve.
     */
    private function syncTaxonomy(Tool $tool, array $frontmatter): void
    {
        $categorySlugs = collect($frontmatter['categories'] ?? [])
            ->filter()
            ->map(fn ($slug) => Str::slug($slug))
            ->all();

        $tagSlugs = collect($frontmatter['tags'] ?? [])
            ->filter()
            ->map(fn ($slug) => Str::slug($slug))
            ->all();

        $categoryIds = Category::whereIn('slug', $categorySlugs)->pluck('id')->all();
        $tagIds = Tag::whereIn('slug', $tagSlugs)->pluck('id')->all();

        $tool->categories()->sync($categoryIds);
        $tool->tags()->sync($tagIds);
    }

    /**
     * Copy the featured image from storage/tools/assets/{slug}/ into the
     * polymorphic Media store. Idempotent by file size + filename — if a
     * primary media already exists matching the source, skip re-upload.
     */
    private function syncFeaturedImage(Tool $tool, array $frontmatter, string $slug): void
    {
        $featured = $frontmatter['featured_image'] ?? null;

        if (empty($featured)) {
            return;
        }

        $sourcePath = $this->getToolsPath()."/assets/{$slug}/{$featured}";

        if (! File::exists($sourcePath)) {
            \Log::warning('Featured image declared in frontmatter but not found on disk', [
                'slug' => $slug,
                'path' => $sourcePath,
            ]);

            return;
        }

        $existing = $tool->getPrimaryMedia('featured');
        $sourceSize = File::size($sourcePath);

        if ($existing && $existing->file_name === $featured && (int) $existing->size === (int) $sourceSize) {
            return; // Already in sync.
        }

        $this->attachMediaFromPath($tool, $sourcePath, 'featured');
    }

    /**
     * Copy a local file into the public disk and create a Media record.
     * Mirrors HasMedia::addMedia() but works from a file path instead of
     * an UploadedFile (since we read from the repo, not a request).
     */
    private function attachMediaFromPath(Tool $tool, string $sourcePath, string $collection): Media
    {
        $extension = pathinfo($sourcePath, PATHINFO_EXTENSION);
        $originalName = basename($sourcePath);
        $filename = Str::uuid().'.'.$extension;

        $modelType = strtolower(class_basename($tool));
        $relativePath = "{$modelType}/{$tool->id}/{$collection}/{$filename}";

        \Storage::disk('public')->put(
            $relativePath,
            File::get($sourcePath)
        );

        // Single-file collection — replace any existing primary.
        if (in_array($collection, ['featured', 'thumbnail'], true)) {
            $tool->media()
                ->where('collection_name', $collection)
                ->get()
                ->each(fn (Media $m) => $tool->deleteMedia($m));
        }

        $media = new Media([
            'collection_name' => $collection,
            'file_name' => $originalName,
            'mime_type' => mime_content_type($sourcePath) ?: 'application/octet-stream',
            'disk' => 'public',
            'path' => $relativePath,
            'size' => File::size($sourcePath),
            'is_primary' => true,
        ]);

        $tool->media()->save($media);

        return $media;
    }

    /**
     * Generate a boilerplate markdown file for a new tool. Mirrors
     * NewsletterService::createNewsletterTemplate().
     */
    public function createToolTemplate(string $title, ?string $websiteUrl = null): string
    {
        $path = $this->getToolsPath();

        if (! File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }

        $slug = Str::slug($title);
        $filePath = "{$path}/{$slug}.md";

        if (File::exists($filePath)) {
            throw new \RuntimeException("Tool file already exists: {$filePath}");
        }

        $websiteUrlLine = $websiteUrl ? "\"{$websiteUrl}\"" : '""';

        $template = <<<MD
---
title: "{$title}"
slug: "{$slug}"
excerpt: ""
business_model: "freemium"

website_url: {$websiteUrlLine}
pricing_url: ""
documentation_url: ""

# Monetization — fill these in ONLY if an affiliate program exists.
# Leave the whole block commented out if there is no affiliate.
# affiliate:
#   url: "https://example.com/?ref=elarquitecto"
#   program: ""
#   commission: ""
#   signup_url: ""
#   notes: ""

# Research / validation record (filled by the publishing playbook).
research:
  score: null
  validated_at: null
  sources: []
  alternatives_considered: []
  why_include: ""

# SEO
meta_title: ""
meta_description: ""
meta_keywords: []

# Taxonomy — use canonical slugs. Scanner fails on unknown ones.
# Valid categories: ai, machine-learning, automation, agents, content-creation, programming
# Valid tags by category:
#   ai: fine-tuning, prompt-engineering
#   agents: multi-agent, reasoning-agent, planning
#   machine-learning: deep-learning, neural-networks, transformers
#   automation: workflow, scripting, task-management
#   content-creation: blog-writing, social-media
#   programming: code-generation, debugging
categories: []
tags: []

# Display
is_featured: false
# featured_image: "featured.png"  # place in storage/tools/assets/{$slug}/

# Publishing — leave null to keep as draft; set to a datetime in the
# timezone below to schedule or publish. Uses America/Tijuana by default.
published_at: null
timezone: "America/Tijuana"
---

## ¿Qué es {$title}?

[Descripción directa de lo que hace. Sin marketing vacío.]

## Características principales

- Primera característica relevante
- Segunda característica
- Tercera característica

## ¿Para quién es?

[Audiencia y caso de uso ideal.]

## Precio

[Modelo de pricing concreto.]

## Mi veredicto

[Fortalezas, debilidades, y si vale la pena la inversión.]

## 💡 Tip práctico

[Una acción concreta que el lector puede ejecutar hoy.]
MD;

        File::put($filePath, $template);

        return $filePath;
    }
}
