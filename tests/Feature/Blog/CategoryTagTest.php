<?php

namespace Tests\Feature\Blog;

use App\Models\BlogCategory;
use App\Models\BlogTag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryTagTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_blog_post_can_be_assigned_to_category()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create a category
        $categoryData = [
            'name' => 'Test Category',
            'description' => 'This is a test category',
        ];

        $this->post(route('root.blog.categories.store'), $categoryData);
        $category = BlogCategory::where('name', 'Test Category')->first();

        // Create a post with the category
        $postData = [
            'title' => 'Categorized Post',
            'content' => 'Test content',
            'excerpt' => 'Test excerpt',
            'category_id' => $category->id,
            'published' => true,
        ];

        $this->post(route('root.blog.store'), $postData);

        $this->assertDatabaseHas('blog_posts', [
            'title' => 'Categorized Post',
            'category_id' => $category->id,
        ]);
    }

    public function test_blog_post_can_be_assigned_multiple_tags()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create tags
        $this->post(route('root.blog.tags.store'), ['name' => 'Tag One']);
        $this->post(route('root.blog.tags.store'), ['name' => 'Tag Two']);

        $tagOne = BlogTag::where('name', 'Tag One')->first();
        $tagTwo = BlogTag::where('name', 'Tag Two')->first();

        // Create a post
        $postData = [
            'title' => 'Tagged Post',
            'content' => 'Test content',
            'excerpt' => 'Test excerpt',
            'category_id' => null,
            'published' => true,
            'tags' => [$tagOne->id, $tagTwo->id],
        ];

        $this->post(route('root.blog.store'), $postData);

        $post = \App\Models\BlogPost::where('title', 'Tagged Post')->first();

        $this->assertDatabaseHas('blog_post_tag', [
            'blog_post_id' => $post->id,
            'blog_tag_id' => $tagOne->id,
        ]);

        $this->assertDatabaseHas('blog_post_tag', [
            'blog_post_id' => $post->id,
            'blog_tag_id' => $tagTwo->id,
        ]);
    }

    public function test_users_can_view_posts_by_category()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create a category
        $categoryData = [
            'name' => 'View Category',
            'description' => 'This is a test category',
        ];

        $this->post(route('root.blog.categories.store'), $categoryData);
        $category = BlogCategory::where('name', 'View Category')->first();

        // Create posts in the category
        $postData = [
            'title' => 'Category Post One',
            'content' => 'Test content',
            'excerpt' => 'Test excerpt',
            'category_id' => $category->id,
            'published' => true,
        ];

        $this->post(route('root.blog.store'), $postData);

        $postData = [
            'title' => 'Category Post Two',
            'content' => 'Test content',
            'excerpt' => 'Test excerpt',
            'category_id' => $category->id,
            'published' => true,
        ];

        $this->post(route('root.blog.store'), $postData);

        // Test as a guest user
        $this->actingAs(User::factory()->create());

        $response = $this->get(route('blog.category', $category));
        $response->assertOk();
        $response->assertSee('Category Post One');
        $response->assertSee('Category Post Two');
    }

    public function test_users_can_view_posts_by_tag()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create a tag
        $this->post(route('root.blog.tags.store'), ['name' => 'View Tag']);
        $tag = BlogTag::where('name', 'View Tag')->first();

        // Create posts with the tag
        $postData = [
            'title' => 'Tag Post One',
            'content' => 'Test content',
            'excerpt' => 'Test excerpt',
            'category_id' => null,
            'published' => true,
            'tags' => [$tag->id],
        ];

        $this->post(route('root.blog.store'), $postData);

        $postData = [
            'title' => 'Tag Post Two',
            'content' => 'Test content',
            'excerpt' => 'Test excerpt',
            'category_id' => null,
            'published' => true,
            'tags' => [$tag->id],
        ];

        $this->post(route('root.blog.store'), $postData);

        // Test as a guest user
        $this->actingAs(User::factory()->create());

        $response = $this->get(route('blog.tag', [
            'tag' => $tag,
        ]));
        $response->assertStatus(200);
        $response->assertSee('Tag Post One');
        $response->assertSee('Tag Post Two');
    }
}
