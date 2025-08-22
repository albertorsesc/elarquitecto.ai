<?php

namespace Tests\Unit\Models;

use App\Enums\ToolBusinessModelEnum;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Tool;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ToolTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_create_a_tool()
    {
        $tool = Tool::factory()->create([
            'title' => 'Test Tool',
            'slug' => 'test-tool',
            'business_model' => ToolBusinessModelEnum::FREE->value,
        ]);

        $this->assertDatabaseHas('tools', [
            'title' => 'Test Tool',
            'slug' => 'test-tool',
            'business_model' => 'free',
        ]);
    }

    #[Test]
    public function it_generates_uuid_automatically()
    {
        $tool = Tool::factory()->create();

        $this->assertNotNull($tool->uuid);
        $this->assertMatchesRegularExpression('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/', $tool->uuid);
    }

    #[Test]
    public function it_generates_slug_from_title_if_not_provided()
    {
        $tool = Tool::create([
            'title' => 'My Awesome Tool',
            'business_model' => ToolBusinessModelEnum::FREE->value,
        ]);

        $this->assertEquals('my-awesome-tool', $tool->slug);
    }

    #[Test]
    public function it_casts_business_model_to_enum()
    {
        $tool = Tool::factory()->create([
            'business_model' => ToolBusinessModelEnum::FREEMIUM->value,
        ]);

        $this->assertInstanceOf(ToolBusinessModelEnum::class, $tool->business_model);
        $this->assertEquals(ToolBusinessModelEnum::FREEMIUM, $tool->business_model);
    }

    #[Test]
    public function it_casts_arrays_properly()
    {
        $tool = Tool::factory()->create([
            'gallery' => ['image1.jpg', 'image2.jpg'],
            'meta_keywords' => ['ai', 'tool', 'productivity'],
            'structured_data' => ['@type' => 'SoftwareApplication'],
        ]);

        $this->assertIsArray($tool->gallery);
        $this->assertIsArray($tool->meta_keywords);
        // structured_data is returned as array from the accessor
        $structuredData = $tool->structured_data;
        $this->assertIsArray($structuredData);
        $this->assertEquals('SoftwareApplication', $structuredData['@type']);
    }

    #[Test]
    public function it_casts_is_featured_to_boolean()
    {
        $tool = Tool::factory()->create(['is_featured' => 1]);
        $this->assertIsBool($tool->is_featured);
        $this->assertTrue($tool->is_featured);

        $tool2 = Tool::factory()->create(['is_featured' => 0]);
        $this->assertIsBool($tool2->is_featured);
        $this->assertFalse($tool2->is_featured);
    }

    #[Test]
    public function it_casts_published_at_to_datetime()
    {
        $date = now()->subDays(5);
        $tool = Tool::factory()->create(['published_at' => $date]);

        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $tool->published_at);
        $this->assertEquals($date->format('Y-m-d H:i:s'), $tool->published_at->format('Y-m-d H:i:s'));
    }

    #[Test]
    public function it_can_have_categories()
    {
        $tool = Tool::factory()->create();
        $categories = Category::factory()->count(3)->create();

        $tool->categories()->attach($categories);

        $this->assertCount(3, $tool->categories);
        $this->assertTrue($tool->categories->contains($categories->first()));
    }

    #[Test]
    public function it_can_have_tags()
    {
        $tool = Tool::factory()->create();
        $tags = Tag::factory()->count(5)->create();

        $tool->tags()->attach($tags);

        $this->assertCount(5, $tool->tags);
        $this->assertTrue($tool->tags->contains($tags->first()));
    }

    #[Test]
    public function it_scopes_published_tools()
    {
        Tool::factory()->count(3)->published()->create();
        Tool::factory()->count(2)->draft()->create();
        Tool::factory()->create(['published_at' => now()->addDays(5)]); // Future

        $publishedTools = Tool::published()->get();

        $this->assertCount(3, $publishedTools);
    }

    #[Test]
    public function it_scopes_featured_tools()
    {
        Tool::factory()->count(2)->featured()->create();
        Tool::factory()->count(3)->create(['is_featured' => false]);

        $featuredTools = Tool::featured()->get();

        $this->assertCount(2, $featuredTools);
    }

    #[Test]
    public function it_returns_meta_title_or_falls_back_to_title()
    {
        $tool1 = Tool::factory()->create([
            'title' => 'Tool Title',
            'meta_title' => 'SEO Title',
        ]);

        $tool2 = Tool::factory()->create([
            'title' => 'Tool Title',
            'meta_title' => null,
        ]);

        $this->assertEquals('SEO Title', $tool1->meta_title);
        $this->assertEquals('Tool Title', $tool2->meta_title);
    }

    #[Test]
    public function it_returns_meta_description_or_falls_back_to_excerpt()
    {
        $tool1 = Tool::factory()->create([
            'excerpt' => 'Tool excerpt',
            'meta_description' => 'SEO description',
        ]);

        $tool2 = Tool::factory()->create([
            'excerpt' => 'Tool excerpt',
            'meta_description' => null,
        ]);

        $this->assertEquals('SEO description', $tool1->meta_description);
        $this->assertEquals('Tool excerpt', $tool2->meta_description);
    }

    #[Test]
    public function it_generates_structured_data_if_not_provided()
    {
        $tool = Tool::factory()->create([
            'title' => 'AI Tool',
            'excerpt' => 'An amazing AI tool',
            'structured_data' => null,
            'business_model' => ToolBusinessModelEnum::FREE->value,
        ]);

        $structuredData = $tool->structured_data;

        $this->assertIsArray($structuredData);
        $this->assertEquals('https://schema.org', $structuredData['@context']);
        $this->assertEquals('SoftwareApplication', $structuredData['@type']);
        $this->assertEquals('AI Tool', $structuredData['name']);
        $this->assertEquals('An amazing AI tool', $structuredData['description']);
        $this->assertEquals('0', $structuredData['offers']['price']);
    }

    #[Test]
    public function it_returns_existing_structured_data_if_provided()
    {
        $customData = ['custom' => 'data'];
        $tool = Tool::factory()->create([
            'structured_data' => $customData,
        ]);

        // The accessor should return the custom data as an array
        $structuredData = $tool->structured_data;
        $this->assertIsArray($structuredData);
        $this->assertEquals($customData['custom'], $structuredData['custom']);
    }

    #[Test]
    public function it_sets_default_business_model_to_free()
    {
        $tool = new Tool([
            'title' => 'Test Tool',
        ]);

        // The default value is set as a string but cast to enum
        $this->assertEquals('free', $tool->getRawOriginal('business_model'));
    }

    #[Test]
    public function it_can_sync_categories()
    {
        $tool = Tool::factory()->create();
        $categories = Category::factory()->count(3)->create();

        $tool->categories()->sync($categories->pluck('id'));
        $this->assertCount(3, $tool->categories);

        $newCategories = Category::factory()->count(2)->create();
        $tool->categories()->sync($newCategories->pluck('id'));

        $tool->refresh();
        $this->assertCount(2, $tool->categories);
        $this->assertTrue($tool->categories->contains($newCategories->first()));
        $this->assertFalse($tool->categories->contains($categories->first()));
    }

    #[Test]
    public function it_can_sync_tags()
    {
        $tool = Tool::factory()->create();
        $tags = Tag::factory()->count(4)->create();

        $tool->tags()->sync($tags->pluck('id'));
        $this->assertCount(4, $tool->tags);

        $newTags = Tag::factory()->count(2)->create();
        $tool->tags()->sync($newTags->pluck('id'));

        $tool->refresh();
        $this->assertCount(2, $tool->tags);
    }

    #[Test]
    public function it_deletes_relationships_on_cascade()
    {
        $tool = Tool::factory()->create();
        $categories = Category::factory()->count(2)->create();
        $tags = Tag::factory()->count(3)->create();

        $tool->categories()->attach($categories);
        $tool->tags()->attach($tags);

        $this->assertDatabaseHas('tool_categories', ['tool_id' => $tool->id]);
        $this->assertDatabaseHas('tool_tags', ['tool_id' => $tool->id]);

        $tool->delete();

        $this->assertDatabaseMissing('tool_categories', ['tool_id' => $tool->id]);
        $this->assertDatabaseMissing('tool_tags', ['tool_id' => $tool->id]);
    }

    #[Test]
    public function it_handles_null_values_properly()
    {
        $tool = Tool::factory()->create([
            'excerpt' => null,
            'description' => null,
            'featured_image' => null,
            'gallery' => null,
            'website_url' => null,
            'pricing_url' => null,
            'documentation_url' => null,
            'meta_title' => null,
            'meta_description' => null,
            'meta_keywords' => null,
            'structured_data' => null,
            'published_at' => null,
        ]);

        $this->assertNull($tool->excerpt);
        $this->assertNull($tool->description);
        $this->assertNull($tool->gallery);
        $this->assertNull($tool->published_at);
    }

    #[Test]
    public function it_validates_unique_slug()
    {
        Tool::factory()->create(['slug' => 'unique-tool']);

        $this->expectException(\Illuminate\Database\QueryException::class);
        Tool::factory()->create(['slug' => 'unique-tool']);
    }

    #[Test]
    public function it_validates_unique_uuid()
    {
        $uuid = '550e8400-e29b-41d4-a716-446655440000';
        Tool::factory()->create(['uuid' => $uuid]);

        $this->expectException(\Illuminate\Database\QueryException::class);
        Tool::factory()->create(['uuid' => $uuid]);
    }
}
