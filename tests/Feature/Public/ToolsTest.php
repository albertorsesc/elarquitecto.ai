<?php

namespace Tests\Feature\Public;

use App\Models\Category;
use App\Models\Tag;
use App\Models\Tool;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ToolsTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function tools_index_page_displays_published_tools()
    {
        // Create categories
        $category1 = Category::factory()->create([
            'name' => 'AI Tools',
            'slug' => 'ai-tools',
        ]);

        $category2 = Category::factory()->create([
            'name' => 'Development',
            'slug' => 'development',
        ]);

        // Create tags
        $tag1 = Tag::factory()->create([
            'category_id' => $category1->id,
            'name' => 'Machine Learning',
            'slug' => 'machine-learning',
        ]);

        $tag2 = Tag::factory()->create([
            'category_id' => $category2->id,
            'name' => 'Code Editor',
            'slug' => 'code-editor',
        ]);

        // Create published tools
        $publishedTool1 = Tool::factory()->published()->create([
            'title' => 'ChatGPT',
            'slug' => 'chatgpt',
            'excerpt' => 'AI language model',
        ]);
        $publishedTool1->categories()->attach($category1);
        $publishedTool1->tags()->attach($tag1);

        $publishedTool2 = Tool::factory()->published()->create([
            'title' => 'VS Code',
            'slug' => 'vs-code',
            'excerpt' => 'Code editor',
        ]);
        $publishedTool2->categories()->attach($category2);
        $publishedTool2->tags()->attach($tag2);

        // Create unpublished tool (should not appear)
        Tool::factory()->draft()->create([
            'title' => 'Unpublished Tool',
            'slug' => 'unpublished-tool',
        ]);

        // Create future published tool (should not appear)
        Tool::factory()->create([
            'title' => 'Future Tool',
            'slug' => 'future-tool',
            'published_at' => now()->addDay(),
        ]);

        $response = $this->get(route('tools.index'));

        $response->assertOk();
        $response->assertViewIs('public.tools.index');
        $response->assertViewHas('tools');

        $tools = $response->viewData('tools');
        $this->assertCount(2, $tools);

        // Check that published tools are displayed
        $response->assertSee('ChatGPT');
        $response->assertSee('VS Code');
        $response->assertSee('AI language model');
        $response->assertSee('Code editor');

        // Check that unpublished tools are not displayed
        $response->assertDontSee('Unpublished Tool');
        $response->assertDontSee('Future Tool');
    }

    #[Test]
    public function tools_index_shows_paginated_results()
    {
        // Create 25 published tools
        Tool::factory()->count(25)->published()->create();

        $response = $this->get(route('tools.index'));

        $response->assertOk();
        $response->assertViewHas('tools');

        $tools = $response->viewData('tools');
        $this->assertEquals(12, $tools->count()); // Default pagination
        $this->assertEquals(25, $tools->total());

        // Check pagination links exist
        $response->assertSee('Next');
    }

    #[Test]
    public function tools_can_be_filtered_by_category()
    {
        $category1 = Category::factory()->create(['slug' => 'ai-tools']);
        $category2 = Category::factory()->create(['slug' => 'development']);

        $tool1 = Tool::factory()->published()->create(['title' => 'AI Tool']);
        $tool1->categories()->attach($category1);

        $tool2 = Tool::factory()->published()->create(['title' => 'Dev Tool']);
        $tool2->categories()->attach($category2);

        $response = $this->get(route('tools.index', ['categoria' => 'ai-tools']));

        $response->assertOk();
        $response->assertSee('AI Tool');
        $response->assertDontSee('Dev Tool');
    }

    #[Test]
    public function tools_can_be_filtered_by_business_model()
    {
        Tool::factory()->free()->published()->create(['title' => 'Free Tool']);
        Tool::factory()->paid()->published()->create(['title' => 'Paid Tool']);
        Tool::factory()->subscription()->published()->create(['title' => 'Subscription Tool']);

        $response = $this->get(route('tools.index', ['modelo' => 'free']));

        $response->assertOk();
        $response->assertSee('Free Tool');
        $response->assertDontSee('Paid Tool');
        $response->assertDontSee('Subscription Tool');
    }

    #[Test]
    public function tools_can_be_searched()
    {
        Tool::factory()->published()->create(['title' => 'ChatGPT Assistant']);
        Tool::factory()->published()->create(['title' => 'GitHub Copilot']);
        Tool::factory()->published()->create(['title' => 'VS Code Editor']);

        $response = $this->get(route('tools.index', ['buscar' => 'ChatGPT']));

        $response->assertOk();
        $response->assertSee('ChatGPT Assistant');
        $response->assertDontSee('GitHub Copilot');
        $response->assertDontSee('VS Code Editor');
    }

    #[Test]
    public function featured_tools_are_shown_first()
    {
        $regularTool = Tool::factory()->published()->create([
            'title' => 'Regular Tool',
            'is_featured' => false,
            'created_at' => now(),
        ]);

        $featuredTool = Tool::factory()->featured()->published()->create([
            'title' => 'Featured Tool',
            'is_featured' => true,
            'created_at' => now()->subDay(),
        ]);

        $response = $this->get(route('tools.index', ['ordenar' => 'populares']));

        $response->assertOk();
        $tools = $response->viewData('tools');

        // Featured tool should appear first despite being older
        $this->assertEquals('Featured Tool', $tools->first()->title);
    }

    #[Test]
    public function tools_show_page_displays_correct_tool()
    {
        $category = Category::factory()->create(['name' => 'AI Tools']);
        $tag = Tag::factory()->create(['name' => 'Machine Learning', 'category_id' => $category->id]);

        $tool = Tool::factory()->published()->create([
            'title' => 'Test Tool',
            'slug' => 'test-tool',
            'excerpt' => 'This is a test tool excerpt',
            'description' => 'This is the full description of the test tool.',
            'website_url' => 'https://example.com',
            'pricing_url' => 'https://example.com/pricing',
            'documentation_url' => 'https://example.com/docs',
            'meta_title' => 'Test Tool - SEO Title',
            'meta_description' => 'SEO description for test tool',
        ]);

        $tool->categories()->attach($category);
        $tool->tags()->attach($tag);

        $response = $this->get(route('tools.show', $tool->slug));

        $response->assertOk();
        $response->assertViewIs('public.tools.show');
        $response->assertViewHas('tool');

        // Check content is displayed
        $response->assertSee('Test Tool');
        $response->assertSee('This is a test tool excerpt');
        $response->assertSee('This is the full description of the test tool.');
        $response->assertSee('AI Tools');
        $response->assertSee('Machine Learning');

        // Check meta tags
        $response->assertSee('Test Tool - SEO Title', false);
        $response->assertSee('SEO description for test tool', false);
    }

    #[Test]
    public function tools_show_returns_404_for_unpublished_tool()
    {
        $tool = Tool::factory()->draft()->create([
            'slug' => 'unpublished-tool',
        ]);

        $response = $this->get(route('tools.show', $tool->slug));

        $response->assertNotFound();
    }

    #[Test]
    public function tools_show_returns_404_for_future_published_tool()
    {
        $tool = Tool::factory()->create([
            'slug' => 'future-tool',
            'published_at' => now()->addDay(),
        ]);

        $response = $this->get(route('tools.show', $tool->slug));

        $response->assertNotFound();
    }

    #[Test]
    public function tools_show_returns_404_for_nonexistent_tool()
    {
        $response = $this->get(route('tools.show', 'nonexistent-tool'));

        $response->assertNotFound();
    }

    #[Test]
    public function related_tools_are_displayed_on_show_page()
    {
        $category = Category::factory()->create();
        $tag1 = Tag::factory()->create(['category_id' => $category->id]);
        $tag2 = Tag::factory()->create(['category_id' => $category->id]);

        // Main tool
        $tool = Tool::factory()->published()->create([
            'title' => 'Main Tool',
            'slug' => 'main-tool',
        ]);
        $tool->categories()->attach($category);
        $tool->tags()->attach([$tag1->id, $tag2->id]);

        // Related tools (share same category)
        $relatedTool1 = Tool::factory()->published()->create(['title' => 'Related Tool 1']);
        $relatedTool1->categories()->attach($category);
        $relatedTool1->tags()->attach($tag1);

        $relatedTool2 = Tool::factory()->published()->create(['title' => 'Related Tool 2']);
        $relatedTool2->categories()->attach($category);
        $relatedTool2->tags()->attach($tag2);

        // Unrelated tool (different category)
        $otherCategory = Category::factory()->create();
        $unrelatedTool = Tool::factory()->published()->create(['title' => 'Unrelated Tool']);
        $unrelatedTool->categories()->attach($otherCategory);

        $response = $this->get(route('tools.show', $tool->slug));

        $response->assertOk();
        $response->assertViewHas('relatedTools');

        $relatedTools = $response->viewData('relatedTools');
        $this->assertCount(2, $relatedTools);

        // Check related tools are displayed
        $response->assertSee('Related Tool 1');
        $response->assertSee('Related Tool 2');

        // Unrelated tool should not be in related tools
        $relatedToolTitles = $relatedTools->pluck('title')->toArray();
        $this->assertNotContains('Unrelated Tool', $relatedToolTitles);
    }

    #[Test]
    public function tools_show_page_has_structured_data()
    {
        $tool = Tool::factory()->published()->create([
            'title' => 'Test Tool',
            'slug' => 'test-tool',
            'excerpt' => 'Tool excerpt',
            'business_model' => 'freemium',
        ]);

        $response = $this->get(route('tools.show', $tool->slug));

        $response->assertOk();

        // Check for JSON-LD structured data in prettified format (with spaces)
        $response->assertSee('"@type": "SoftwareApplication"', false);
        $response->assertSee('"name": "Test Tool"', false);
        $response->assertSee('"applicationCategory": "WebApplication"', false);
    }

    #[Test]
    public function tools_markdown_endpoint_returns_markdown_format()
    {
        $tool = Tool::factory()->published()->create([
            'title' => 'Test Tool',
            'slug' => 'test-tool',
            'excerpt' => 'Tool excerpt',
            'description' => '## Features\n\n- Feature 1\n- Feature 2',
            'website_url' => 'https://example.com',
            'business_model' => 'freemium',
        ]);

        $url = route('tools.markdown', $tool->slug);
        $response = $this->get($url);

        $response->assertOk();
        $response->assertHeader('Content-Type', 'text/markdown; charset=UTF-8');

        // Check markdown content
        $content = $response->getContent();
        $this->assertStringContainsString('# Test Tool', $content);
        $this->assertStringContainsString('Tool excerpt', $content);
        $this->assertStringContainsString('## Features', $content);
        $this->assertStringContainsString('[Sitio web](https://example.com)', $content);
        $this->assertStringContainsString('## Modelo de negocio', $content);
        $this->assertStringContainsString('Freemium', $content);
    }

    #[Test]
    public function tools_index_shows_business_model_filters()
    {
        Tool::factory()->free()->published()->create();
        Tool::factory()->freemium()->published()->create();
        Tool::factory()->paid()->published()->create();

        $response = $this->get(route('tools.index'));

        $response->assertOk();

        // Check that business model filter options are available
        $response->assertSee('Gratis');
        $response->assertSee('Freemium');
        $response->assertSee('Pago');
    }

    #[Test]
    public function tools_index_shows_category_filters_based_on_actual_usage()
    {
        $usedCategory = Category::factory()->create(['name' => 'Used Category']);
        $unusedCategory = Category::factory()->create(['name' => 'Unused Category']);

        $tool = Tool::factory()->published()->create();
        $tool->categories()->attach($usedCategory);

        $response = $this->get(route('tools.index'));

        $response->assertOk();

        // Only categories actually used by published tools should be shown
        $response->assertSee('Used Category');
        $response->assertDontSee('Unused Category');
    }
}
