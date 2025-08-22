<?php

namespace Tests\Feature\Root;

use App\Enums\ToolBusinessModelEnum;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Tool;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ToolsTest extends TestCase
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

    #[Test]
    public function root_user_can_view_tools_index()
    {
        // Create some tools with different states
        Tool::factory()->count(3)->create(['published_at' => now()]);
        Tool::factory()->count(2)->create(['published_at' => null]);

        $response = $this->get(route('root.tools.index'));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $assert) => $assert
            ->component('Root/Tools/Index')
            ->has('tools.data', 5)
        );
    }

    #[Test]
    public function tools_index_can_be_filtered_by_search()
    {
        Tool::factory()->create(['title' => 'ChatGPT Alternative', 'published_at' => now()]);
        Tool::factory()->create(['title' => 'Image Generator', 'published_at' => now()]);
        Tool::factory()->create(['title' => 'Code Assistant', 'published_at' => now()]);

        $response = $this->get(route('root.tools.index', ['search' => 'ChatGPT']));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $assert) => $assert
            ->component('Root/Tools/Index')
            ->has('tools.data', 1)
            ->where('tools.data.0.title', 'ChatGPT Alternative')
        );
    }

    #[Test]
    public function tools_index_can_be_filtered_by_business_model()
    {
        Tool::factory()->free()->published()->create();
        Tool::factory()->freemium()->published()->create();
        Tool::factory()->paid()->published()->create();

        $response = $this->get(route('root.tools.index', ['business_model' => 'free']));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $assert) => $assert
            ->component('Root/Tools/Index')
            ->has('tools.data', 1)
            ->where('tools.data.0.business_model', 'free')
        );
    }

    #[Test]
    public function root_user_can_view_create_tool_page()
    {
        $categories = Category::factory()->count(3)->create();
        $tags = Tag::factory()->count(5)->create();

        $response = $this->get(route('root.tools.create'));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $assert) => $assert
            ->component('Root/Tools/Create')
            ->has('categories')
            ->has('tags')
        );
    }

    #[Test]
    public function root_user_can_create_tool()
    {
        $categories = Category::factory()->count(2)->create();
        $tags = Tag::factory()->count(3)->create();

        $toolData = [
            'title' => 'AI Writing Assistant',
            'slug' => 'ai-writing-assistant',
            'excerpt' => 'A powerful AI tool for content creation',
            'description' => 'This is a comprehensive description of the AI writing assistant tool.',
            'business_model' => ToolBusinessModelEnum::FREEMIUM->value,
            'website_url' => 'https://example.com',
            'pricing_url' => 'https://example.com/pricing',
            'documentation_url' => 'https://example.com/docs',
            'meta_title' => 'AI Writing Assistant - Best Tool',
            'meta_description' => 'Discover the best AI writing assistant',
            'meta_keywords' => ['ai', 'writing', 'assistant'],
            'categories' => [$categories[0]->id, $categories[1]->id],
            'tags' => [$tags[0]->id, $tags[1]->id],
            'is_featured' => true,
            'published_at' => now()->format('Y-m-d\TH:i'),
        ];

        $response = $this->post(route('root.tools.store'), $toolData);

        $response->assertRedirect(route('root.tools.index'));
        $response->assertSessionHas('success', 'Herramienta creada exitosamente.');

        $this->assertDatabaseHas('tools', [
            'title' => 'AI Writing Assistant',
            'slug' => 'ai-writing-assistant',
            'business_model' => 'freemium',
            'is_featured' => true,
        ]);

        $tool = Tool::where('slug', 'ai-writing-assistant')->first();
        $this->assertCount(2, $tool->categories);
        $this->assertCount(2, $tool->tags);
    }

    #[Test]
    public function slug_is_auto_generated_if_not_provided()
    {
        $toolData = [
            'title' => 'My Amazing Tool',
            'excerpt' => 'Tool excerpt',
            'business_model' => ToolBusinessModelEnum::FREE->value,
            'published_at' => now()->format('Y-m-d\TH:i'),
        ];

        $response = $this->post(route('root.tools.store'), $toolData);

        $response->assertRedirect();
        $this->assertDatabaseHas('tools', [
            'title' => 'My Amazing Tool',
            'slug' => 'my-amazing-tool',
        ]);
    }

    #[Test]
    public function root_user_can_view_tool_details()
    {
        $tool = Tool::factory()->create();
        $categories = Category::factory()->count(2)->create();
        $tags = Tag::factory()->count(3)->create();

        $tool->categories()->attach($categories);
        $tool->tags()->attach($tags);

        $response = $this->get(route('root.tools.show', $tool->slug));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $assert) => $assert
            ->component('Root/Tools/Show')
            ->has('tool')
            ->where('tool.id', $tool->id)
            ->where('tool.slug', $tool->slug)
        );
    }

    #[Test]
    public function root_user_can_view_edit_tool_page()
    {
        $tool = Tool::factory()->create();

        $response = $this->get(route('root.tools.edit', $tool->slug));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $assert) => $assert
            ->component('Root/Tools/Edit')
            ->has('tool')
            ->has('categories')
            ->has('tags')
            ->where('tool.id', $tool->id)
        );
    }

    #[Test]
    public function root_user_can_update_tool()
    {
        $tool = Tool::factory()->create([
            'title' => 'Original Title',
            'business_model' => ToolBusinessModelEnum::FREE->value,
        ]);

        $updateData = [
            'title' => 'Updated Title',
            'slug' => $tool->slug,
            'excerpt' => 'Updated excerpt',
            'description' => 'Updated description',
            'business_model' => ToolBusinessModelEnum::PAID->value,
            'is_featured' => true,
            'published_at' => now()->format('Y-m-d\TH:i'),
        ];

        $response = $this->put(route('root.tools.update', $tool->slug), $updateData);

        $response->assertRedirect(route('root.tools.index'));
        $response->assertSessionHas('success', 'Herramienta actualizada exitosamente.');

        $this->assertDatabaseHas('tools', [
            'id' => $tool->id,
            'title' => 'Updated Title',
            'business_model' => 'paid',
            'is_featured' => true,
        ]);
    }

    #[Test]
    public function slug_is_updated_when_title_changes()
    {
        $tool = Tool::factory()->create([
            'title' => 'Original Title',
            'slug' => 'original-title',
        ]);

        $updateData = [
            'title' => 'Completely New Title',
            'excerpt' => 'Some excerpt',
            'business_model' => ToolBusinessModelEnum::FREE->value,
            'published_at' => now()->format('Y-m-d\TH:i'),
        ];

        $response = $this->put(route('root.tools.update', $tool->slug), $updateData);

        $response->assertRedirect();

        $tool->refresh();
        $this->assertEquals('completely-new-title', $tool->slug);
    }

    #[Test]
    public function root_user_can_delete_tool()
    {
        $tool = Tool::factory()->create();

        $response = $this->delete(route('root.tools.destroy', $tool->slug));

        $response->assertRedirect(route('root.tools.index'));
        $response->assertSessionHas('success', 'Herramienta eliminada exitosamente.');

        $this->assertDatabaseMissing('tools', ['id' => $tool->id]);
    }

    #[Test]
    public function root_user_cannot_create_tool_with_invalid_data()
    {
        $response = $this->post(route('root.tools.store'), [
            'title' => '', // Required field
            'business_model' => 'invalid_model', // Invalid enum
        ]);

        $response->assertSessionHasErrors(['title', 'business_model']);
    }

    #[Test]
    public function validation_for_url_fields()
    {
        $response = $this->post(route('root.tools.store'), [
            'title' => 'Test Tool',
            'business_model' => ToolBusinessModelEnum::FREE->value,
            'website_url' => 'not-a-valid-url',
            'pricing_url' => 'also-invalid',
            'documentation_url' => 'invalid-docs-url',
            'published_at' => now()->format('Y-m-d\TH:i'),
        ]);

        $response->assertSessionHasErrors(['website_url', 'pricing_url', 'documentation_url']);
    }

    #[Test]
    public function root_user_can_assign_categories_and_tags_to_tool()
    {
        $categories = Category::factory()->count(3)->create();
        $tags = Tag::factory()->count(5)->create();

        $toolData = [
            'title' => 'Tool with Relations',
            'excerpt' => 'Tool excerpt',
            'business_model' => ToolBusinessModelEnum::SUBSCRIPTION->value,
            'categories' => [$categories[0]->id, $categories[2]->id],
            'tags' => [$tags[0]->id, $tags[2]->id, $tags[4]->id],
            'published_at' => now()->format('Y-m-d\TH:i'),
        ];

        $response = $this->post(route('root.tools.store'), $toolData);
        $response->assertRedirect();

        $tool = Tool::where('title', 'Tool with Relations')->first();
        $this->assertCount(2, $tool->categories);
        $this->assertCount(3, $tool->tags);
        $this->assertTrue($tool->categories->contains($categories[0]));
        $this->assertTrue($tool->tags->contains($tags[4]));
    }

    #[Test]
    public function featured_tools_functionality()
    {
        Tool::factory()->count(3)->create(['is_featured' => false]);
        $featuredTools = Tool::factory()->count(2)->create(['is_featured' => true]);

        $featured = Tool::where('is_featured', true)->get();

        $this->assertCount(2, $featured);
        foreach ($featuredTools as $tool) {
            $this->assertTrue($featured->contains('id', $tool->id));
        }
    }

    #[Test]
    public function published_vs_draft_tools()
    {
        Tool::factory()->count(3)->create(['published_at' => now()]);
        Tool::factory()->count(2)->create(['published_at' => null]);

        $published = Tool::whereNotNull('published_at')->count();
        $drafts = Tool::whereNull('published_at')->count();

        $this->assertEquals(3, $published);
        $this->assertEquals(2, $drafts);
    }

    #[Test]
    public function non_root_users_cannot_access_tools_management()
    {
        // Create a regular user (not root)
        $regularUser = User::factory()->create([
            'email' => 'regular@example.com',
        ]);

        $this->actingAs($regularUser);

        $tool = Tool::factory()->create();

        // Test all routes
        $this->get(route('root.tools.index'))->assertStatus(403);
        $this->get(route('root.tools.create'))->assertStatus(403);
        $this->post(route('root.tools.store'), [])->assertStatus(403);
        $this->get(route('root.tools.show', $tool->slug))->assertStatus(403);
        $this->get(route('root.tools.edit', $tool->slug))->assertStatus(403);
        $this->put(route('root.tools.update', $tool->slug), [])->assertStatus(403);
        $this->delete(route('root.tools.destroy', $tool->slug))->assertStatus(403);
    }

    #[Test]
    public function guest_users_cannot_access_tools_management()
    {
        // Sign out the root user
        auth()->logout();

        $tool = Tool::factory()->create();

        // Test all routes - guests should be redirected to login
        $this->get(route('root.tools.index'))->assertRedirect('/login');
        $this->get(route('root.tools.create'))->assertRedirect('/login');
        $this->post(route('root.tools.store'), [])->assertRedirect('/login');
        $this->get(route('root.tools.show', $tool->slug))->assertRedirect('/login');
        $this->get(route('root.tools.edit', $tool->slug))->assertRedirect('/login');
        $this->put(route('root.tools.update', $tool->slug), [])->assertRedirect('/login');
        $this->delete(route('root.tools.destroy', $tool->slug))->assertRedirect('/login');
    }

    #[Test]
    public function duplicate_slug_handling()
    {
        Tool::factory()->create([
            'title' => 'Existing Tool',
            'slug' => 'existing-tool',
        ]);

        // Create tool with same title, should get different slug
        $toolData = [
            'title' => 'Existing Tool',
            'excerpt' => 'New tool with same title',
            'business_model' => ToolBusinessModelEnum::FREE->value,
            'published_at' => now()->format('Y-m-d\TH:i'),
        ];

        $response = $this->post(route('root.tools.store'), $toolData);
        $response->assertRedirect();

        // The new tool should have the same slug since HasSlug trait handles uniqueness
        $tools = Tool::where('title', 'Existing Tool')->get();
        $this->assertCount(2, $tools);
        $slugs = $tools->pluck('slug')->toArray();
        $this->assertContains('existing-tool', $slugs);
        $this->assertContains('existing-tool-1', $slugs);
    }

    #[Test]
    public function meta_keywords_handling()
    {
        $toolData = [
            'title' => 'Tool with Keywords',
            'excerpt' => 'Tool excerpt',
            'business_model' => ToolBusinessModelEnum::FREE->value,
            'meta_keywords' => ['ai', 'machine learning', 'automation'],
            'published_at' => now()->format('Y-m-d\TH:i'),
        ];

        $response = $this->post(route('root.tools.store'), $toolData);
        $response->assertRedirect();

        $tool = Tool::where('title', 'Tool with Keywords')->first();
        $this->assertIsArray($tool->meta_keywords);
        $this->assertCount(3, $tool->meta_keywords);
        $this->assertContains('machine learning', $tool->meta_keywords);
    }

    #[Test]
    public function business_model_edge_cases()
    {
        $validModels = ToolBusinessModelEnum::cases();

        foreach ($validModels as $model) {
            $tool = Tool::factory()->create(['business_model' => $model->value]);
            $this->assertEquals($model, $tool->business_model);
        }

        // Test invalid business model
        $response = $this->post(route('root.tools.store'), [
            'title' => 'Invalid Model Tool',
            'business_model' => 'invalid_model',
            'published_at' => now()->format('Y-m-d\TH:i'),
        ]);

        $response->assertSessionHasErrors(['business_model']);
    }

    #[Test]
    public function very_long_content_handling()
    {
        $longDescription = trim(str_repeat('This is a very long description. ', 1000));

        $toolData = [
            'title' => 'Tool with Long Content',
            'excerpt' => str_repeat('Excerpt ', 50),
            'description' => $longDescription,
            'business_model' => ToolBusinessModelEnum::FREE->value,
            'published_at' => now()->format('Y-m-d\TH:i'),
        ];

        $response = $this->post(route('root.tools.store'), $toolData);
        $response->assertRedirect();

        $tool = Tool::where('title', 'Tool with Long Content')->first();
        $this->assertEquals($longDescription, $tool->description);
    }

    #[Test]
    public function special_characters_in_title_and_slug()
    {
        $toolData = [
            'title' => 'Tool with Special Characters: & % $ # @ ! ()',
            'excerpt' => 'Tool excerpt',
            'business_model' => ToolBusinessModelEnum::FREE->value,
            'published_at' => now()->format('Y-m-d\TH:i'),
        ];

        $response = $this->post(route('root.tools.store'), $toolData);
        $response->assertRedirect();

        $tool = Tool::where('title', 'Tool with Special Characters: & % $ # @ ! ()')->first();
        $this->assertNotNull($tool);
        // Slug should be sanitized
        $this->assertMatchesRegularExpression('/^[a-z0-9\-]+$/', $tool->slug);
    }

    #[Test]
    public function tool_with_all_optional_fields_empty()
    {
        $toolData = [
            'title' => 'Minimal Tool',
            'business_model' => ToolBusinessModelEnum::FREE->value,
            'published_at' => now()->format('Y-m-d\TH:i'),
            // All other fields are optional
        ];

        $response = $this->post(route('root.tools.store'), $toolData);
        $response->assertRedirect();

        $tool = Tool::where('title', 'Minimal Tool')->first();
        $this->assertNotNull($tool);
        $this->assertNull($tool->excerpt);
        $this->assertNull($tool->description);
        $this->assertNull($tool->website_url);
        $this->assertFalse($tool->is_featured);
    }

    #[Test]
    public function pagination_works_correctly()
    {
        // Create more tools than the pagination limit
        Tool::factory()->count(25)->create(['published_at' => now()]);

        $response = $this->get(route('root.tools.index'));

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $assert) => $assert
            ->component('Root/Tools/Index')
            ->has('tools.data', 20) // Default pagination is 20
            ->has('tools.links')
        );

        // Test second page
        $response = $this->get(route('root.tools.index', ['page' => 2]));
        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $assert) => $assert
            ->has('tools.data', 5) // Remaining 5 tools
        );
    }

    #[Test]
    public function ordering_by_created_at_desc()
    {
        $oldTool = Tool::factory()->create([
            'title' => 'Old Tool',
            'created_at' => now()->subDays(10),
        ]);

        $newTool = Tool::factory()->create([
            'title' => 'New Tool',
            'created_at' => now(),
        ]);

        $response = $this->get(route('root.tools.index'));

        $response->assertInertia(fn (AssertableInertia $assert) => $assert
            ->where('tools.data.0.id', $newTool->id)
            ->where('tools.data.1.id', $oldTool->id)
        );
    }

    #[Test]
    public function uuid_is_automatically_generated()
    {
        $toolData = [
            'title' => 'Tool with UUID',
            'business_model' => ToolBusinessModelEnum::FREE->value,
            'published_at' => now()->format('Y-m-d\TH:i'),
        ];

        $response = $this->post(route('root.tools.store'), $toolData);
        $response->assertRedirect();

        $tool = Tool::where('title', 'Tool with UUID')->first();
        $this->assertNotNull($tool->uuid);
        $this->assertMatchesRegularExpression('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/', $tool->uuid);
    }

    #[Test]
    public function cannot_update_tool_that_does_not_exist()
    {
        $response = $this->put(route('root.tools.update', 'non-existent-slug'), [
            'title' => 'Updated Title',
            'business_model' => ToolBusinessModelEnum::FREE->value,
        ]);

        $response->assertNotFound();
    }

    #[Test]
    public function cannot_delete_tool_that_does_not_exist()
    {
        $response = $this->delete(route('root.tools.destroy', 'non-existent-slug'));

        $response->assertNotFound();
    }
}
