<?php

namespace Tests\Feature\Root;

use App\Models\Prompt;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class PromptsTest extends TestCase
{
    use RefreshDatabase;
    
    /**
     * Setup a root user for testing
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->signInAsRoot();
    }
    
    /**
     * Test root user can view prompts index page
     */
    public function test_root_user_can_view_prompts_index()
    {
        $response = $this->get(route('root.prompts.index'));
        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $assert) => $assert->component('Root/Prompts/Index'));
    }
    
    /**
     * Test root user can view the create prompt page
     */
    public function test_root_user_can_view_create_prompt_page()
    {
        $response = $this->get(route('root.prompts.create'));
        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $assert) => $assert->component('Root/Prompts/Create'));
    }
    
    /**
     * Test root user can create a new prompt
     */
    public function test_root_user_can_create_prompt()
    {
        $prompt = $this->make(Prompt::class, [
            'published_at' => null,
        ]);

        $response = $this->post(
            route('root.prompts.store'),
            $prompt->toArray()
        );

        $response->assertRedirect(route('root.prompts.index'));
        $response->assertSessionHas('success', 'Prompt created successfully');
        
        $this->assertDatabaseHas('prompts', [
            'title' => $prompt->title,
            'slug' => $prompt->slug,
            'excerpt' => $prompt->excerpt,
            'content' => $prompt->content,
            'published_at' => null,
            'word_count' => $prompt->word_count,
            'target_models' => json_encode($prompt->target_models),
        ]);
        $this->assertNull($prompt->published_at);
    }
    
    /**
     * Test root user can view a specific prompt details
     */
    public function test_root_user_can_view_prompt_details()
    {
        $prompt = $this->create(Prompt::class);

        $response = $this->get(route('root.prompts.show', $prompt));
        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $assert) => $assert->component('Root/Prompts/Show'));
    }
    
    /**
     * Test root user can view the edit prompt page
     */
    public function test_root_user_can_view_edit_prompt_page()
    {
        $prompt = $this->create(Prompt::class);

        $response = $this->get(route('root.prompts.edit', $prompt));
        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $assert) => $assert->component('Root/Prompts/Edit'));
    }
    
    /**
     * Test root user can update an existing prompt
     */
    public function test_root_user_can_update_prompt()
    {
        $prompt = $this->create(Prompt::class);

        $response = $this->put(route('root.prompts.update', $prompt), [
            'title' => 'Updated Prompt',
            'slug' => 'updated-prompt',
            'excerpt' => 'Updated Excerpt',
            'content' => 'Updated Content',
            'published_at' => '2025-01-01',
            'word_count' => 100,
            'target_models' => ['gpt-4o', 'gpt-4'],
        ]);

        $response->assertRedirect(route('root.prompts.show', $prompt));
        $response->assertSessionHas('success', 'Prompt updated successfully');

        $this->assertDatabaseHas('prompts', [
            'title' => 'Updated Prompt',
            'slug' => 'updated-prompt',
            'excerpt' => 'Updated Excerpt',
            'content' => 'Updated Content',
            'published_at' => '2025-01-01 00:00:00',
            'word_count' => 100,
            'target_models' => json_encode(['gpt-4o', 'gpt-4']),
        ]);
    }
    
    /**
     * Test root user can delete a prompt
     */
    public function test_root_user_can_delete_prompt()
    {
        // Test implementation
    }
    
    /**
     * Test validation when creating a prompt with invalid data
     */
    public function test_root_user_cannot_create_prompt_with_invalid_data()
    {
        // Test implementation
    }
    
    /**
     * Test validation when updating a prompt with invalid data
     */
    public function test_root_user_cannot_update_prompt_with_invalid_data()
    {
        // Test implementation
    }
    
    /**
     * Test root user can assign categories to a prompt
     */
    public function test_root_user_can_assign_categories_to_prompt()
    {
        // Test implementation
    }
    
    /**
     * Test root user can assign tags to a prompt
     */
    public function test_root_user_can_assign_tags_to_prompt()
    {
        // Test implementation
    }
}
