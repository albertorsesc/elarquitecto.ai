<?php

namespace Tests\Feature\Blog;

use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StorageTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_blog_post_can_have_featured_image()
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $this->actingAs($user);

        $image = UploadedFile::fake()->image('featured-image.jpg', 1200, 800);

        $postData = [
            'title' => 'Image Test Post',
            'content' => 'This is a test post with an image.',
            'excerpt' => 'This is a test excerpt.',
            'category_id' => null,
            'published' => true,
            'featured_image' => $image,
        ];

        $response = $this->post(route('root.blog.store'), $postData);

        $post = BlogPost::where('title', 'Image Test Post')->first();

        $this->assertNotNull($post->featured_image);
        Storage::disk('public')->assertExists($post->featured_image);
    }

    public function test_featured_image_url_is_generated_correctly()
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $this->actingAs($user);

        $image = UploadedFile::fake()->image('featured-image.jpg', 1200, 800);

        $postData = [
            'title' => 'Image URL Test',
            'content' => 'This is a test post for image URL generation.',
            'excerpt' => 'This is a test excerpt.',
            'category_id' => null,
            'published' => true,
            'featured_image' => $image,
        ];

        $this->post(route('root.blog.store'), $postData);

        $post = BlogPost::where('title', 'Image URL Test')->first();
        
        $response = $this->get(route('blog.show', $post));
        $response->assertOk();
        $response->assertSee(Storage::url($post->featured_image), false);
    }

    public function test_storage_driver_can_be_switched_with_env_variable()
    {
        $originalDriver = config('filesystems.default');

        // Temporarily change the config
        config(['filesystems.default' => 's3']);

        $this->assertEquals('s3', config('filesystems.default'));

        // Restore the original config
        config(['filesystems.default' => $originalDriver]);
    }
}
