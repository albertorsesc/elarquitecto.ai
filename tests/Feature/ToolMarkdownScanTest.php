<?php

namespace Tests\Feature;

use App\Models\Tag;
use App\Models\Tool;
use App\Services\ToolMarkdownService;
use Database\Seeders\CategoryAndTagSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ToolMarkdownScanTest extends TestCase
{
    use RefreshDatabase;

    private string $toolsPath;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed canonical taxonomy (categories + tags).
        $this->seed(CategoryAndTagSeeder::class);

        // Point the service at an isolated temp dir so tests don't touch
        // real storage/tools/ content.
        $this->toolsPath = storage_path('tools-test-'.uniqid());
        File::makeDirectory($this->toolsPath, 0755, true);

        $this->app->bind(ToolMarkdownService::class, function () {
            $service = new class extends ToolMarkdownService
            {
                public string $pathOverride;

                public function getToolsPath(): string
                {
                    return $this->pathOverride;
                }
            };
            $service->pathOverride = $this->toolsPath;

            return $service;
        });

        Storage::fake('public');
    }

    protected function tearDown(): void
    {
        if (File::exists($this->toolsPath)) {
            File::deleteDirectory($this->toolsPath);
        }

        parent::tearDown();
    }

    public function test_scans_and_creates_a_tool_from_markdown_file(): void
    {
        $this->writeToolFile('cursor', $this->validFrontmatter('Cursor', 'cursor'));

        $service = app(ToolMarkdownService::class);
        $result = $service->scanForTools();

        $this->assertSame(1, $result['processed']);
        $this->assertSame(0, $result['skipped']);
        $this->assertSame(0, $result['failed']);

        $tool = Tool::where('slug', 'cursor')->first();
        $this->assertNotNull($tool);
        $this->assertSame('Cursor', $tool->title);
        $this->assertSame('Editor AI', $tool->excerpt);
    }

    public function test_is_idempotent_when_file_is_unchanged(): void
    {
        $this->writeToolFile('cursor', $this->validFrontmatter('Cursor', 'cursor'));

        $service = app(ToolMarkdownService::class);

        $first = $service->scanForTools();
        $second = $service->scanForTools();

        $this->assertSame(1, $first['processed']);
        $this->assertSame(1, $second['skipped']);
        $this->assertSame(0, $second['processed']);
    }

    public function test_reprocesses_when_file_content_changes(): void
    {
        $this->writeToolFile('cursor', $this->validFrontmatter('Cursor', 'cursor'));

        $service = app(ToolMarkdownService::class);
        $service->scanForTools();

        $this->writeToolFile('cursor', str_replace(
            'Editor AI',
            'Editor AI actualizado',
            $this->validFrontmatter('Cursor', 'cursor')
        ));

        $result = $service->scanForTools();

        $this->assertSame(1, $result['processed']);
        $this->assertSame('Editor AI actualizado', Tool::where('slug', 'cursor')->first()->excerpt);
    }

    public function test_syncs_categories_and_tags_from_frontmatter(): void
    {
        $this->writeToolFile('cursor', $this->validFrontmatter('Cursor', 'cursor'));

        app(ToolMarkdownService::class)->scanForTools();

        $tool = Tool::where('slug', 'cursor')->first();

        $this->assertEqualsCanonicalizing(
            ['ai', 'codigo'],
            $tool->categories->pluck('slug')->all()
        );

        $this->assertEqualsCanonicalizing(
            ['cli', 'prompt-engineering'],
            $tool->tags->pluck('slug')->all()
        );
    }

    public function test_rejects_unknown_category_slugs(): void
    {
        $frontmatter = str_replace(
            'categories: ["ai", "codigo"]',
            'categories: ["nonexistent"]',
            $this->validFrontmatter('Cursor', 'cursor')
        );
        $this->writeToolFile('cursor', $frontmatter);

        $result = app(ToolMarkdownService::class)->scanForTools();

        $this->assertSame(0, $result['processed']);
        $this->assertSame(1, $result['failed']);
        $this->assertDatabaseMissing('tools', ['slug' => 'cursor']);
    }

    public function test_rejects_unknown_tag_slugs(): void
    {
        $frontmatter = str_replace(
            'tags: ["cli", "prompt-engineering"]',
            'tags: ["invented-tag"]',
            $this->validFrontmatter('Cursor', 'cursor')
        );
        $this->writeToolFile('cursor', $frontmatter);

        $result = app(ToolMarkdownService::class)->scanForTools();

        $this->assertSame(1, $result['failed']);
        $this->assertDatabaseMissing('tools', ['slug' => 'cursor']);
    }

    public function test_rolls_back_on_failure_so_no_partial_tool_persists(): void
    {
        // Mix: one valid, one with bad tag. Both should yield the valid one
        // persisted and the bad one rolled back with no orphan row.
        $this->writeToolFile('good', $this->validFrontmatter('Good Tool', 'good'));

        $bad = str_replace(
            'tags: ["cli", "prompt-engineering"]',
            'tags: ["does-not-exist"]',
            $this->validFrontmatter('Bad Tool', 'bad')
        );
        $this->writeToolFile('bad', $bad);

        $result = app(ToolMarkdownService::class)->scanForTools();

        $this->assertSame(1, $result['processed']);
        $this->assertSame(1, $result['failed']);

        $this->assertDatabaseHas('tools', ['slug' => 'good']);
        $this->assertDatabaseMissing('tools', ['slug' => 'bad']);
    }

    public function test_affiliate_data_is_stored_and_display_url_uses_it(): void
    {
        $this->writeToolFile('cursor', $this->validFrontmatter('Cursor', 'cursor'));

        app(ToolMarkdownService::class)->scanForTools();

        $tool = Tool::where('slug', 'cursor')->first();

        $this->assertTrue($tool->has_affiliate_link);
        $this->assertSame('https://cursor.sh/?ref=test', $tool->display_url);
        $this->assertSame('Cursor Partner', $tool->affiliate_data['program']);
    }

    public function test_display_url_falls_back_to_website_when_no_affiliate(): void
    {
        $frontmatter = preg_replace(
            '/^affiliate:.*?(?=^[a-z])/ms',
            '',
            $this->validFrontmatter('Cursor', 'cursor')
        );
        $this->writeToolFile('cursor', $frontmatter);

        app(ToolMarkdownService::class)->scanForTools();

        $tool = Tool::where('slug', 'cursor')->first();

        $this->assertFalse($tool->has_affiliate_link);
        $this->assertSame('https://cursor.sh', $tool->display_url);
    }

    public function test_requires_title_and_slug_in_frontmatter(): void
    {
        File::put("{$this->toolsPath}/broken.md", "---\ntitle: \"No Slug\"\n---\n\nBody\n");

        $result = app(ToolMarkdownService::class)->scanForTools();

        $this->assertSame(1, $result['failed']);
    }

    private function writeToolFile(string $slug, string $content): void
    {
        File::put("{$this->toolsPath}/{$slug}.md", $content);
    }

    private function validFrontmatter(string $title, string $slug): string
    {
        return <<<MD
---
title: "{$title}"
slug: "{$slug}"
excerpt: "Editor AI"
business_model: "subscription"
website_url: "https://cursor.sh"
pricing_url: "https://cursor.sh/pricing"

affiliate:
  url: "https://cursor.sh/?ref=test"
  program: "Cursor Partner"
  commission: "20% recurring"

research:
  score: 9
  validated_at: "2026-04-20"
  sources:
    - "https://example.com/review"
  why_include: "Market leader"

categories: ["ai", "codigo"]
tags: ["cli", "prompt-engineering"]

is_featured: false
published_at: null
---

## Body

Content here.
MD;
    }
}
