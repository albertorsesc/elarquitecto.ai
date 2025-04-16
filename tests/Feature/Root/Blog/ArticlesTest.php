<?php

namespace Tests\Feature\Root\Blog;

use App\Models\Blog\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class ArticlesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->signInAsRoot();
    }

    public function test_root_can_view_articles()
    {
        $this->get(route('root.blog.articles.index'))
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('Root/Blog/Articles/Index')
                ->has('articles')
            );
    }

    public function test_root_can_view_create_article()
    {
        $this->get(route('root.blog.articles.create'))
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('Root/Blog/Articles/Create'));
    }

    public function test_root_can_create_article()
    {
        $newArticle = $this->make(Article::class, [
            'uuid' => null,
            'author_id' => null,
        ]);

        $response = $this->post(route('root.blog.articles.store'), $newArticle->toArray());
        $response->assertRedirect(route('root.blog.articles.index'));

        $createdArticle = Article::first();
        $this->assertDatabaseHas('articles', [
            'uuid' => $createdArticle->uuid,
            'author_id' => $createdArticle->author_id,
            'title' => $newArticle->title,
            'slug' => $createdArticle->slug,
            'body' => $newArticle->body,
            'published_at' => $newArticle->published_at,
            'is_pinned' => $newArticle->is_pinned,
            'is_featured' => $newArticle->is_featured,
            'original_url' => $newArticle->original_url,
            'hero_image_url' => $newArticle->hero_image_url,
            'hero_image_author_name' => $newArticle->hero_image_author_name,
            'hero_image_author_url' => $newArticle->hero_image_author_url,
        ]);
    }

    public function test_root_user_can_view_article()
    {
        $article = $this->create(Article::class);

        $response = $this->get(route('root.blog.articles.show', $article->slug));
        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Root/Blog/Articles/Show')
            ->has('article')
        );
    }

    public function test_root_user_can_view_article_edit_page()
    {
        $article = $this->create(Article::class);

        $response = $this->get(route('root.blog.articles.edit', $article->slug));
        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Root/Blog/Articles/Edit')
            ->has('article'));
    }

    public function test_root_user_can_update_article()
    {
        $article = $this->create(Article::class);
        $updatedArticle = $this->make(Article::class, [
            'uuid' => $article->uuid,
            'author_id' => $article->author_id,
        ]);

        $response = $this->put(route('root.blog.articles.update', $article), $updatedArticle->toArray());

        // Get the fresh model with the updated slug
        $updatedModel = $article->fresh();
        $response->assertRedirect(route('root.blog.articles.show', $updatedModel));

        $this->assertDatabaseHas('articles', [
            'uuid' => $article->uuid,
            'author_id' => $article->author_id,
            'title' => $updatedArticle->title,
            'slug' => $updatedModel->slug, // Use the updated slug
            'body' => $updatedArticle->body,
        ]);
    }

    public function test_article_slug_is_updated_when_title_changes()
    {
        $article = $this->create(Article::class);
        $originalSlug = $article->slug;

        // Update article with a new title
        $newTitle = 'This is a completely different title';
        $response = $this->put(route('root.blog.articles.update', $article), [
            'title' => $newTitle,
            'body' => $article->body,
            'is_pinned' => $article->is_pinned,
            'is_featured' => $article->is_featured,
        ]);

        $response->assertRedirect(route('root.blog.articles.show', $article->fresh()));

        $updatedArticle = $article->fresh();

        // Assert the slug has changed
        $this->assertNotEquals($originalSlug, $updatedArticle->slug);

        // Assert the new slug is derived from the new title
        $this->assertEquals(Str::slug($newTitle), $updatedArticle->slug);
    }
}
