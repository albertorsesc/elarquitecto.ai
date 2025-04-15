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
        $this->markTestSkipped('This test is not implemented yet.');
    }

    public function test_prompts_can_be_filtered_by_category()
    {
        $this->markTestSkipped('This test is not implemented yet.');
    }

    public function test_prompts_can_be_filtered_by_tag()
    {
        $this->markTestSkipped('This test is not implemented yet.');
    }

    public function test_prompts_show_page_displays_correct_prompt()
    {
        $this->markTestSkipped('This test is not implemented yet.');
    }

    public function test_prompts_show_returns_404_for_unpublished_prompt()
    {
        $this->markTestSkipped('This test is not implemented yet.');
    }

    public function test_prompts_show_returns_404_for_nonexistent_prompt()
    {
        $this->markTestSkipped('This test is not implemented yet.');
    }

    public function test_related_prompts_are_displayed_on_show_page()
    {
        $this->markTestSkipped('This test is not implemented yet.');
    }
}
