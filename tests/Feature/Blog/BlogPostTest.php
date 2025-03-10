<?php

namespace Tests\Feature\Blog;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BlogPostTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_blog_post_can_be_created_with_html_content()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $this->actingAs($user);

        $postData = [
            'title' => 'Test Blog Post',
            'content' => '<h1>Heading 1</h1><p>This is a paragraph with <strong>bold</strong> and <em>italic</em> text.</p>',
            'excerpt' => 'This is a test excerpt',
            'category_id' => null,
            'published' => false,
        ];

        $response = $this->post(route('admin.blog.posts.store'), $postData);

        $response->assertRedirect();
        $this->assertDatabaseHas('blog_posts', [
            'title' => 'Test Blog Post',
            'content' => '<h1>Heading 1</h1><p>This is a paragraph with <strong>bold</strong> and <em>italic</em> text.</p>',
            'excerpt' => 'This is a test excerpt',
        ]);
    }

    public function test_blog_post_generates_slug_automatically()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $postData = [
            'title' => 'This Is A Test Title With Spaces and Special Characters!',
            'content' => 'Test content',
            'excerpt' => 'Test excerpt',
            'category_id' => null,
            'published' => false,
        ];

        $this->post(route('admin.blog.posts.store'), $postData);

        $this->assertDatabaseHas('blog_posts', [
            'title' => 'This Is A Test Title With Spaces and Special Characters!',
            'slug' => 'this-is-a-test-title-with-spaces-and-special-characters',
        ]);
    }

    public function test_blog_post_can_be_published()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $postData = [
            'title' => 'Draft Post',
            'content' => 'Test content',
            'excerpt' => 'Test excerpt',
            'category_id' => null,
            'published' => false,
        ];

        $response = $this->post(route('admin.blog.posts.store'), $postData);

        $post = \App\Models\BlogPost::where('title', 'Draft Post')->first();

        $updateData = [
            'title' => 'Draft Post',
            'content' => 'Test content',
            'excerpt' => 'Test excerpt',
            'category_id' => null,
            'published' => true,
        ];

        $this->put(route('admin.blog.posts.update', $post->id), $updateData);

        $this->assertDatabaseHas('blog_posts', [
            'title' => 'Draft Post',
            'published' => true,
            'published_at' => now()->toDateTimeString(),
        ]);
    }

    public function test_published_blog_posts_are_visible_to_users()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $postData = [
            'title' => 'Published Post',
            'content' => 'Test content',
            'excerpt' => 'Test excerpt',
            'category_id' => null,
            'published' => true,
        ];

        $this->post(route('admin.blog.posts.store'), $postData);

        // Test as a guest user
        $this->actingAs(User::factory()->create());

        $response = $this->get(route('blog.index'));
        $response->assertStatus(200);
        $response->assertSee('Published Post');

        $response = $this->get(route('blog.show', 'published-post'));
        $response->assertStatus(200);
        $response->assertSee('Published Post');
    }

    public function test_draft_blog_posts_are_not_visible_to_users()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $postData = [
            'title' => 'Draft Post',
            'content' => 'Test content',
            'excerpt' => 'Test excerpt',
            'category_id' => null,
            'published' => false,
        ];

        $this->post(route('admin.blog.posts.store'), $postData);

        // Test as a guest user
        $this->actingAs(User::factory()->create());

        $response = $this->get(route('blog.index'));
        $response->assertStatus(200);
        $response->assertDontSee('Draft Post');

        $response = $this->get(route('blog.show', 'draft-post'));
        $response->assertStatus(404);
    }
}