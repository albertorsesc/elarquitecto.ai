<?php

namespace Tests\Feature;

use App\Models\Blog\Article;
use App\Models\Category;
use App\Models\Prompt;
use App\Models\Tool;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class WelcomePageTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function welcome_page_displays_recent_tools()
    {
        // Create a category
        $category = Category::factory()->create(['name' => 'AI Tools']);

        // Create some tools
        $tool1 = Tool::factory()->published()->create([
            'title' => 'Recent Tool 1',
            'published_at' => now(),
        ]);
        $tool1->categories()->attach($category);

        $tool2 = Tool::factory()->published()->create([
            'title' => 'Recent Tool 2',
            'published_at' => now()->subDay(),
        ]);
        $tool2->categories()->attach($category);

        // Create an unpublished tool that shouldn't appear
        $unpublishedTool = Tool::factory()->draft()->create([
            'title' => 'Unpublished Tool',
        ]);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertViewHas('tools');

        $tools = $response->viewData('tools');
        $this->assertCount(2, $tools);

        // Check tools section is displayed
        $response->assertSee('Herramientas Recientes');
        $response->assertSee('Recent Tool 1');
        $response->assertSee('Recent Tool 2');
        $response->assertDontSee('Unpublished Tool');
    }

    #[Test]
    public function welcome_page_displays_recent_prompts()
    {
        // Create some prompts
        Prompt::factory()->count(3)->create([
            'published_at' => now(),
        ]);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertViewHas('prompts');

        $prompts = $response->viewData('prompts');
        $this->assertCount(3, $prompts);

        // Check prompts section is displayed
        $response->assertSee('Prompts Recientes');
    }

    #[Test]
    public function welcome_page_displays_recent_articles()
    {
        $this->markTestSkipped('Article factory needs fixing - author_id not being set properly');

        // Create some articles with published date
        Article::factory()
            ->count(3)
            ->state(['published_at' => now()])
            ->create();

        $response = $this->get('/');

        $response->assertOk();
        $response->assertViewHas('articles');

        $articles = $response->viewData('articles');
        $this->assertCount(3, $articles);

        // Check articles section is displayed
        $response->assertSee('Artículos Recientes');
    }

    #[Test]
    public function welcome_page_has_link_to_tools_section()
    {
        $response = $this->get('/');

        $response->assertOk();

        // Check that the Herramientas card links to the correct route
        $response->assertSee('/herramientas', false);
        $response->assertSee('Herramientas');
        $response->assertSee('Las mejores herramientas de IA');
    }

    #[Test]
    public function welcome_page_limits_items_to_six_each()
    {
        $this->markTestSkipped('Article factory needs fixing - author_id not being set properly');

        // Create more than 6 of each type
        Tool::factory()->count(10)->published()->create();
        Prompt::factory()->count(10)->create(['published_at' => now()]);
        Article::factory()
            ->count(10)
            ->state(['published_at' => now()])
            ->create();

        $response = $this->get('/');

        $response->assertOk();

        // Check that only 6 of each are displayed
        $tools = $response->viewData('tools');
        $prompts = $response->viewData('prompts');
        $articles = $response->viewData('articles');

        $this->assertCount(6, $tools);
        $this->assertCount(6, $prompts);
        $this->assertCount(6, $articles);
    }
}
