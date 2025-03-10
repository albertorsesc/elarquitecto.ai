<?php

namespace Tests\Feature\Blog;

use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SeoTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_blog_post_has_meta_title_and_description()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $postData = [
            'title' => 'SEO Test Post',
            'content' => 'This is the content of the SEO test post.',
            'excerpt' => 'This is a test excerpt for SEO purposes.',
            'category_id' => null,
            'published' => true,
        ];

        $this->post(route('root.blog.store'), $postData);

        $response = $this->get(route('blog.show', BlogPost::first()));

        $response->assertStatus(200);
        $response->assertSee('<title>SEO Test Post | El Arquitecto A.I.</title>', false);
        $response->assertSee('<meta name="description" content="This is a test excerpt for SEO purposes."', false);
    }

    public function test_blog_post_has_open_graph_tags()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $postData = [
            'title' => 'Open Graph Test',
            'content' => 'This is the content of the Open Graph test post.',
            'excerpt' => 'This is a test excerpt for Open Graph purposes.',
            'category_id' => null,
            'published' => true,
        ];

        $this->post(route('root.blog.store'), $postData);

        $response = $this->get(route('blog.show', BlogPost::first()));

        $response->assertStatus(200);
        $response->assertSee('<meta property="og:title" content="Open Graph Test"', false);
        $response->assertSee('<meta property="og:description" content="This is a test excerpt for Open Graph purposes."', false);
        $response->assertSee('<meta property="og:type" content="article"', false);
        $response->assertSee('<meta property="og:url" content="', false);
    }

    public function test_blog_post_has_canonical_url()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $postData = [
            'title' => 'Canonical URL Test',
            'content' => 'This is the content of the canonical URL test post.',
            'excerpt' => 'This is a test excerpt for canonical URL purposes.',
            'category_id' => null,
            'published' => true,
        ];

        $this->post(route('root.blog.store'), $postData);

        $response = $this->get(route('blog.show', BlogPost::first()));

        $response->assertOk();
        $response->assertSee('<link rel="canonical" href="', false);
    }
}
