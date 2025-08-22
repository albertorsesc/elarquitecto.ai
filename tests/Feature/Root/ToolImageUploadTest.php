<?php

namespace Tests\Feature\Root;

use App\Models\Tool;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ToolImageUploadTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->signInAsRoot();
        Storage::fake('public');
    }

    #[Test]
    public function root_user_can_upload_featured_image_when_creating_tool()
    {
        $image = UploadedFile::fake()->image('tool.jpg', 800, 600);

        $response = $this->post(route('root.tools.store'), [
            'title' => 'Tool with Image',
            'excerpt' => 'Test tool with image upload',
            'business_model' => 'free',
            'featured_image' => $image,
            'published_at' => now()->format('Y-m-d\TH:i'),
        ]);

        $response->assertRedirect(route('root.tools.index'));

        $tool = Tool::where('title', 'Tool with Image')->first();
        $this->assertNotNull($tool);

        // Check that the tool has a featured image URL
        $this->assertNotNull($tool->featured_image_url);

        // Check that media was created
        $this->assertDatabaseHas('media', [
            'mediable_id' => $tool->id,
            'mediable_type' => Tool::class,
            'collection_name' => 'featured',
        ]);
    }

    #[Test]
    public function root_user_can_update_featured_image()
    {
        $tool = Tool::factory()->create(['title' => 'Original Tool']);

        // First add an image
        $image1 = UploadedFile::fake()->image('image1.jpg', 800, 600);
        $tool->addMedia($image1, 'featured');

        $this->assertDatabaseHas('media', [
            'mediable_id' => $tool->id,
            'collection_name' => 'featured',
        ]);

        // Now update with a new image using the POST route (for file uploads)
        $image2 = UploadedFile::fake()->image('image2.jpg', 800, 600);

        $response = $this->post(route('root.tools.update.post', $tool->slug), [
            '_method' => 'PUT',
            'title' => 'Updated Tool',
            'excerpt' => 'Updated excerpt',
            'business_model' => 'freemium',
            'featured_image' => $image2,
            'published_at' => now()->format('Y-m-d\TH:i'),
        ]);

        $response->assertRedirect(route('root.tools.index'));

        // Should still only have one media record (old one replaced)
        $mediaCount = $tool->media()
            ->where('collection_name', 'featured')
            ->count();
        $this->assertEquals(1, $mediaCount);
    }

    #[Test]
    public function root_user_can_update_tool_without_image_using_put()
    {
        $tool = Tool::factory()->create(['title' => 'Original Tool']);

        // Update without image using PUT route
        $response = $this->put(route('root.tools.update', $tool->slug), [
            'title' => 'Updated Tool via PUT',
            'excerpt' => 'Updated excerpt',
            'business_model' => 'paid',
            'published_at' => now()->format('Y-m-d\TH:i'),
        ]);

        $response->assertRedirect(route('root.tools.index'));

        $tool->refresh();
        $this->assertEquals('Updated Tool via PUT', $tool->title);
        $this->assertEquals('paid', $tool->business_model->value);
    }

    #[Test]
    public function featured_image_upload_validates_file_type()
    {
        $invalidFile = UploadedFile::fake()->create('document.pdf', 100);

        $response = $this->post(route('root.tools.store'), [
            'title' => 'Tool with Invalid File',
            'business_model' => 'free',
            'featured_image' => $invalidFile,
            'published_at' => now()->format('Y-m-d\TH:i'),
        ]);

        $response->assertSessionHasErrors(['featured_image']);
    }

    #[Test]
    public function featured_image_upload_validates_file_size()
    {
        // Create a file larger than 5MB (5120 KB)
        $largeImage = UploadedFile::fake()->image('large.jpg')->size(6000);

        $response = $this->post(route('root.tools.store'), [
            'title' => 'Tool with Large Image',
            'business_model' => 'free',
            'featured_image' => $largeImage,
            'published_at' => now()->format('Y-m-d\TH:i'),
        ]);

        $response->assertSessionHasErrors(['featured_image']);
    }

    #[Test]
    public function tool_can_be_created_without_featured_image()
    {
        $response = $this->post(route('root.tools.store'), [
            'title' => 'Tool without Image',
            'excerpt' => 'No image provided',
            'business_model' => 'free',
            'published_at' => now()->format('Y-m-d\TH:i'),
        ]);

        $response->assertRedirect(route('root.tools.index'));

        $tool = Tool::where('title', 'Tool without Image')->first();
        $this->assertNotNull($tool);
        $this->assertNull($tool->featured_image_url);
    }
}
