<?php

namespace Tests\Feature\Root;

use App\Enums\PromptCategoryEnum;
use App\Enums\PromptTagEnum;
use App\Models\Category;
use App\Models\Prompt;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
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
        
        // Create category and tags using prompt-specific values
        $category = $this->createPromptCategory(PromptCategoryEnum::CONTENT_CREATION->value);
        $tag1 = $this->createPromptTag($category, PromptTagEnum::BLOG_WRITING->value);
        $tag2 = $this->createPromptTag($category, PromptTagEnum::SOCIAL_MEDIA->value);

        $response = $this->post(
            route('root.prompts.store'),
            $prompt->toArray() + [
                'category_id' => $category->id,
                'tags' => [$tag1->id, $tag2->id],
            ]
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
        
        // Check category and tags were assigned
        $createdPrompt = Prompt::where('slug', $prompt->slug)->first();
        $this->assertTrue($createdPrompt->hasCategory($category));
        $this->assertTrue($createdPrompt->hasTag($tag1));
        $this->assertTrue($createdPrompt->hasTag($tag2));
    }
    
    /**
     * Test root user can view a specific prompt details
     */
    public function test_root_user_can_view_prompt_details()
    {
        // Create a prompt with valid category and tag
        $prompt = $this->create(Prompt::class);
        $category = $this->createPromptCategory();
        $tag = $this->createPromptTag($category);
        $prompt->setCategory($category);
        $prompt->setTags([$tag->id]);

        $response = $this->get(route('root.prompts.show', $prompt));
        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $assert) => $assert->component('Root/Prompts/Show'));
    }
    
    /**
     * Test root user can view the edit prompt page
     */
    public function test_root_user_can_view_edit_prompt_page()
    {
        // Create a prompt with valid category and tag
        $prompt = $this->create(Prompt::class);
        $category = $this->createPromptCategory();
        $tag = $this->createPromptTag($category);
        $prompt->setCategory($category);
        $prompt->setTags([$tag->id]);

        $response = $this->get(route('root.prompts.edit', $prompt));
        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $assert) => $assert->component('Root/Prompts/Edit'));
    }
    
    /**
     * Test root user can update an existing prompt
     */
    public function test_root_user_can_update_prompt()
    {
        // Create category and tag using prompt-specific values
        $category = $this->createPromptCategory(PromptCategoryEnum::CONTENT_CREATION->value);
        $tag = $this->createPromptTag($category, PromptTagEnum::BLOG_WRITING->value);
        
        // Create prompt
        $prompt = $this->create(Prompt::class);
        $prompt->setCategory($category);
        $prompt->setTags([$tag->id]);

        $response = $this->put(route('root.prompts.update', $prompt), [
            'title' => 'Updated Prompt',
            'slug' => 'updated-prompt',
            'excerpt' => 'Updated Excerpt',
            'content' => 'Updated Content',
            'published_at' => '2025-01-01',
            'word_count' => 100,
            'target_models' => ['gpt-4o', 'gpt-4'],
            'category_id' => $category->id,
            'tags' => [$tag->id],
        ]);

        $response->assertRedirect(route('root.prompts.show', 'updated-prompt'));
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
        
        // Check category and tags were preserved
        $prompt = Prompt::where('slug', 'updated-prompt')->first();
        $this->assertTrue($prompt->hasCategory($category));
        $this->assertTrue($prompt->hasTag($tag));
    }
    
    /**
     * Test root user can delete a prompt
     */
    public function test_root_user_can_delete_prompt()
    {
        // Create a prompt with valid category and tag
        $prompt = $this->create(Prompt::class);
        $category = $this->createPromptCategory();
        $tag = $this->createPromptTag($category);
        $prompt->setCategory($category);
        $prompt->setTags([$tag->id]);

        $response = $this->delete(route('root.prompts.destroy', $prompt));

        $response->assertRedirect(route('root.prompts.index'));
        $response->assertSessionHas('success', 'Prompt deleted successfully');

        $this->assertModelMissing($prompt);
    }
    
    /**
     * Test validation when creating a prompt with invalid data
     */
    public function test_root_user_cannot_create_prompt_with_invalid_data()
    {
        $response = $this->post(route('root.prompts.store'), [
            'title' => '',
            'slug' => '',
            'excerpt' => '',
            'content' => '',
            'published_at' => '',
            'word_count' => '',
            'target_models' => 'not-an-array',
        ]);

        $response->assertSessionHasErrors([
            'title',
            'slug',
            'excerpt',
            'content',
            'word_count',
            'target_models',
        ]);
    }
    
    /**
     * Test validation when updating a prompt with invalid data
     */
    public function test_root_user_cannot_update_prompt_with_invalid_data()
    {
        // Create a prompt with valid category and tag
        $prompt = $this->create(Prompt::class);
        $category = $this->createPromptCategory();
        $tag = $this->createPromptTag($category);
        $prompt->setCategory($category);
        $prompt->setTags([$tag->id]);

        $response = $this->put(route('root.prompts.update', $prompt), [
            'title' => '',
            'slug' => '',
            'excerpt' => '',
            'content' => '',
            'published_at' => '',
            'word_count' => '',
            'target_models' => 'not-an-array',
        ]);

        $response->assertSessionHasErrors([
            'title',
            'slug',
            'excerpt',
            'content',
            'word_count',
            'target_models',
        ]);
    }
    
    /**
     * Test root user can only assign prompt-specific categories and tags
     */
    public function test_root_user_can_only_use_prompt_specific_categories_and_tags()
    {
        // Create a prompt
        $prompt = $this->make(Prompt::class);
        
        // Get categories and tags from PromptCategoryEnum and PromptTagEnum
        $category = $this->createPromptCategory(PromptCategoryEnum::CONTENT_CREATION->value);
        
        // Create a prompt-specific tag (from PromptTagEnum)
        $promptTag = $this->createPromptTag($category, PromptTagEnum::BLOG_WRITING->value);
        
        // Create a non-prompt-specific tag (one that doesn't exist in PromptTagEnum)
        $nonPromptTag = $this->create(Tag::class, [
            'name' => 'Not A Prompt Tag',
            'slug' => 'not-a-prompt-tag',
            'category_id' => $category->id,
        ]);

        // Test creating with valid prompt-specific tag
        $response = $this->post(
            route('root.prompts.store'),
            $prompt->toArray() + [
                'category_id' => $category->id,
                'tags' => [$promptTag->id],
            ]
        );
        
        $response->assertRedirect(route('root.prompts.index'));
        $response->assertSessionHas('success', 'Prompt created successfully');
        
        // Test creating with non-prompt tag
        $prompt2 = $this->make(Prompt::class, ['slug' => 'prompt-with-non-prompt-tag']);
        
        // Make the request and expect a validation error
        $response = $this->post(
            route('root.prompts.store'),
            $prompt2->toArray() + [
                'category_id' => $category->id,
                'tags' => [$nonPromptTag->id],
            ]
        );
        
        // This should have validation errors due to the non-prompt tag
        $response->assertSessionHasErrors(['tags.0']);
    }
    
    /**
     * Test root user can assign categories to a prompt
     */
    public function test_root_user_can_assign_categories_to_prompt()
    {
        $prompt = $this->make(Prompt::class, [
            'excerpt' => 'Short valid excerpt to avoid validation errors'
        ]);
        $category = $this->createPromptCategory();
        
        // Create at least one valid prompt tag
        $tag = $this->createPromptTag($category);

        $response = $this->post(
            route('root.prompts.store'),
            $prompt->toArray() + [
                'category_id' => $category->id,
                'tags' => [$tag->id],
            ]
        );

        $response->assertRedirect(route('root.prompts.index'));
        $response->assertSessionHas('success', 'Prompt created successfully');
        
        // Verify the prompt was created with the correct category
        $createdPrompt = Prompt::where('slug', $prompt->slug)->first();
        $this->assertTrue($createdPrompt->hasCategory($category));
    }
    
    /**
     * Test root user can assign tags to a prompt
     */
    public function test_root_user_can_assign_tags_to_prompt()
    {
        // Create category first with a valid prompt category
        $category = $this->createPromptCategory(PromptCategoryEnum::PROGRAMMING->value);
        
        // Create tags that belong to this category using prompt-specific tags
        $tag1 = $this->createPromptTag($category, PromptTagEnum::CODE_GENERATION->value);
        $tag2 = $this->createPromptTag($category, PromptTagEnum::DEBUGGING->value);
        
        $prompt = $this->make(Prompt::class);

        $response = $this->post(
            route('root.prompts.store'),
            $prompt->toArray() + [
                'category_id' => $category->id,
                'tags' => [$tag1->id, $tag2->id],
            ]
        );

        $response->assertRedirect(route('root.prompts.index'));
        $response->assertSessionHas('success', 'Prompt created successfully');
        
        // Verify the prompt was created with the correct tags
        $createdPrompt = Prompt::where('slug', $prompt->slug)->first();
        $this->assertTrue($createdPrompt->hasTag($tag1));
        $this->assertTrue($createdPrompt->hasTag($tag2));
    }
    
    /**
     * Test root user can update a prompt with category and tags
     */
    public function test_root_user_can_update_prompt_with_category_and_tags()
    {
        // Create initial category and prompt with valid prompt-specific values
        $category = $this->createPromptCategory(PromptCategoryEnum::CONTENT_CREATION->value);
        $prompt = $this->create(Prompt::class);
        $prompt->setCategory($category);
        
        // Create tag for initial category
        $tag1 = $this->createPromptTag($category, PromptTagEnum::BLOG_WRITING->value);
        $prompt->setTags([$tag1->id]);
        
        // Create new category and tag for update with valid prompt-specific values
        $newCategory = $this->createPromptCategory(PromptCategoryEnum::PROGRAMMING->value);
        $newTag = $this->createPromptTag($newCategory, PromptTagEnum::CODE_GENERATION->value);

        $response = $this->put(route('root.prompts.update', $prompt), [
            'title' => 'Updated Prompt',
            'slug' => 'updated-prompt',
            'excerpt' => 'Updated Excerpt',
            'content' => 'Updated Content',
            'published_at' => '2025-01-01',
            'word_count' => 100,
            'target_models' => ['gpt-4o', 'gpt-4'],
            'category_id' => $newCategory->id,
            'tags' => [$newTag->id], // Switch to tag from new category
        ]);

        $response->assertRedirect(route('root.prompts.show', 'updated-prompt'));
        $response->assertSessionHas('success', 'Prompt updated successfully');

        $this->assertDatabaseHas('prompts', [
            'title' => 'Updated Prompt',
            'slug' => 'updated-prompt',
        ]);
        
        // Refresh the prompt model
        $prompt->refresh();
        
        // Check category was updated
        $this->assertTrue($prompt->hasCategory($newCategory));
        $this->assertFalse($prompt->hasCategory($category));
        
        // Check tags were updated - should have newTag but not tag1
        $this->assertTrue($prompt->hasTag($newTag));
        $this->assertFalse($prompt->hasTag($tag1));
    }

    /**
     * Create a valid prompt tag for testing
     */
    private function createPromptTag(Category $category, ?string $tagName = null): Tag
    {
        // If no specific tag requested, use a random one from PromptTagEnum
        if ($tagName === null) {
            $tagValue = fake()->randomElement(PromptTagEnum::values());
        } else {
            $tagValue = $tagName;
        }
        
        return $this->create(Tag::class, [
            'name' => $tagValue,
            'slug' => Str::slug($tagValue),
            'category_id' => $category->id,
        ]);
    }
    
    /**
     * Create a valid prompt category for testing
     */
    private function createPromptCategory(?string $categoryName = null): Category
    {
        // If no specific category requested, use a random one from PromptCategoryEnum
        if ($categoryName === null) {
            $categoryValue = fake()->randomElement(PromptCategoryEnum::values());
        } else {
            $categoryValue = $categoryName;
        }
        
        return $this->create(Category::class, [
            'name' => $categoryValue,
            'slug' => Str::slug($categoryValue),
        ]);
    }
}
