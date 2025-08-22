<?php

namespace Tests\Feature\Public;

use App\Models\Prompt;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PromptsTest extends TestCase
{
    use RefreshDatabase;

    public function test_prompts_index_page_displays_published_prompts()
    {
        // Create published prompts
        $publishedPrompt1 = $this->create(Prompt::class, [
            'title' => 'Published Prompt 1',
            'published_at' => now()->subDays(1),
        ]);

        $publishedPrompt2 = $this->create(Prompt::class, [
            'title' => 'Published Prompt 2',
            'published_at' => now()->subDays(2),
        ]);

        // Create unpublished prompts (published_at is null)
        $unpublishedPrompt = $this->create(Prompt::class, [
            'title' => 'Unpublished Prompt',
            'published_at' => null,
        ]);

        // Visit the prompts index page
        $response = $this->get(route('prompts.index'));

        // Assert response is successful
        $response->assertStatus(200);

        // Assert published prompts are visible
        $response->assertSee('Published Prompt 1');
        $response->assertSee('Published Prompt 2');

        // Assert unpublished prompts are not visible
        $response->assertDontSee('Unpublished Prompt');
    }

    public function test_prompts_index_shows_paginated_results()
    {
        // Create 20 published prompts (more than typical page size of 15)
        for ($i = 1; $i <= 20; $i++) {
            $this->create(Prompt::class, [
                'title' => "Prompt {$i}",
                'published_at' => now()->subDays($i),
            ]);
        }

        // Get the first page
        $response = $this->get(route('prompts.index'));

        $response->assertStatus(200);

        // First page should show recent prompts
        $response->assertSee('Prompt 1');

        // Check that not all prompts are shown on first page (pagination is working)
        // Since we created 20 prompts and page size is 15, Prompt 20 shouldn't be on page 1
        $response->assertDontSee('Prompt 20');

        // Get the second page if pagination is working
        $response2 = $this->get(route('prompts.index', ['page' => 2]));
        $response2->assertStatus(200);

        // Second page should have the older prompts
        $response2->assertSee('Prompt 16');
    }

    public function test_prompts_can_be_filtered_by_category()
    {
        // Create categories
        $category1 = \App\Models\Category::factory()->create(['name' => 'Category 1', 'slug' => 'category-1']);
        $category2 = \App\Models\Category::factory()->create(['name' => 'Category 2', 'slug' => 'category-2']);

        // Create prompts with different categories
        $prompt1 = $this->create(Prompt::class, [
            'title' => 'Prompt in Category 1',
            'published_at' => now()->subDay(),
        ]);
        $prompt1->setCategory($category1);

        $prompt2 = $this->create(Prompt::class, [
            'title' => 'Prompt in Category 2',
            'published_at' => now()->subDay(),
        ]);
        $prompt2->setCategory($category2);

        // Filter by category 1
        $response = $this->get(route('prompts.index', ['categoria' => 'category-1']));

        $response->assertStatus(200);
        $response->assertSee('Prompt in Category 1');
        $response->assertDontSee('Prompt in Category 2');

        // Filter by category 2
        $response2 = $this->get(route('prompts.index', ['categoria' => 'category-2']));

        $response2->assertStatus(200);
        $response2->assertSee('Prompt in Category 2');
        $response2->assertDontSee('Prompt in Category 1');
    }

    public function test_prompts_can_be_filtered_by_tag()
    {
        // Create tags
        $tag1 = \App\Models\Tag::factory()->create(['name' => 'Tag 1', 'slug' => 'tag-1']);
        $tag2 = \App\Models\Tag::factory()->create(['name' => 'Tag 2', 'slug' => 'tag-2']);

        // Create prompts with different tags
        $prompt1 = $this->create(Prompt::class, [
            'title' => 'Prompt with Tag 1',
            'published_at' => now()->subDay(),
        ]);
        $prompt1->tags()->attach($tag1);

        $prompt2 = $this->create(Prompt::class, [
            'title' => 'Prompt with Tag 2',
            'published_at' => now()->subDay(),
        ]);
        $prompt2->tags()->attach($tag2);

        // Filter by tag 1
        $response = $this->get(route('prompts.index', ['etiqueta' => 'tag-1']));

        $response->assertStatus(200);
        $response->assertSee('Prompt with Tag 1');
        $response->assertDontSee('Prompt with Tag 2');

        // Filter by tag 2
        $response2 = $this->get(route('prompts.index', ['etiqueta' => 'tag-2']));

        $response2->assertStatus(200);
        $response2->assertSee('Prompt with Tag 2');
        $response2->assertDontSee('Prompt with Tag 1');
    }

    public function test_prompts_show_page_displays_correct_prompt()
    {
        // Create a published prompt
        $prompt = $this->create(Prompt::class, [
            'title' => 'Test Prompt Title',
            'slug' => 'test-prompt-slug',
            'excerpt' => 'This is the test excerpt',
            'content' => 'This is the full content of the prompt',
            'published_at' => now()->subDay(),
        ]);

        // Visit the prompt show page
        $response = $this->get(route('prompts.show', $prompt));

        // Assert response is successful
        $response->assertStatus(200);

        // Assert prompt details are visible
        $response->assertSee('Test Prompt Title');
        $response->assertSee('This is the test excerpt');
        $response->assertSee('This is the full content of the prompt');
    }

    public function test_prompts_show_returns_404_for_unpublished_prompt()
    {
        // Create an unpublished prompt (published_at is null)
        $prompt = $this->create(Prompt::class, [
            'title' => 'Unpublished Prompt',
            'slug' => 'unpublished-prompt',
            'published_at' => null,
        ]);

        // Try to visit the unpublished prompt
        $response = $this->get(route('prompts.show', $prompt));

        // Assert it returns 404
        $response->assertStatus(404);
    }

    public function test_prompts_show_returns_404_for_nonexistent_prompt()
    {
        // Try to visit a non-existent prompt
        $response = $this->get('/prompts/non-existent-prompt-slug');

        // Assert it returns 404
        $response->assertStatus(404);
    }

    public function test_related_prompts_are_displayed_on_show_page()
    {
        // Create main prompt
        $mainPrompt = $this->create(Prompt::class, [
            'title' => 'Main Prompt',
            'slug' => 'main-prompt',
            'published_at' => now()->subDay(),
        ]);

        // Create related prompts
        $relatedPrompt1 = $this->create(Prompt::class, [
            'title' => 'Related Prompt 1',
            'published_at' => now()->subDay(),
        ]);

        $relatedPrompt2 = $this->create(Prompt::class, [
            'title' => 'Related Prompt 2',
            'published_at' => now()->subDay(),
        ]);

        // Visit the main prompt show page
        $response = $this->get(route('prompts.show', $mainPrompt));

        // Assert response is successful
        $response->assertStatus(200);

        // Assert main prompt is visible
        $response->assertSee('Main Prompt');

        // Assert related prompts are visible in the related section
        $response->assertSee('Related Prompt 1');
        $response->assertSee('Related Prompt 2');
    }
}
