<?php

namespace Tests\Feature\Root;

use App\Models\Blog\Article;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class ArticlesTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthorized_user_cannot_access_root_article_pages()
    {
        $this->actingAs(User::factory()->create());

        $response = $this->get(route('root.articles.index'));
        $response->assertRedirect(route('dashboard'));

        $response = $this->get(route('root.articles.create'));
        $response->assertRedirect(route('dashboard'));

        $response = $this->post(route('root.articles.store', $this->make(Article::class)->toArray()));
        $response->assertRedirect(route('dashboard'));

        $article = $this->create(Article::class);
        $response = $this->post(
            route('root.articles.store', $article->toArray()),
            $this->make(Article::class)->toArray()
        );
        $response->assertRedirect(route('dashboard'));

        $response = $this->put(route('root.articles.publish', $article));
        $response->assertRedirect(route('dashboard'));

        $response = $this->get(route('root.articles.edit', $this->create(Article::class)));
        $response->assertRedirect(route('dashboard'));
    }

    public function test_authorized_user_can_create_an_article()
    {
        $this->signInAsRoot();

        // Create tags
        $tags = $this->create(Tag::class, [], 3);
        $tagIds = $tags->pluck('id')->toArray();

        $articleData = $this->make(Article::class, ['author_id' => null]);

        $data = array_merge($articleData->toArray(), [
            'tags' => $tagIds,
        ]);

        $response = $this->post(route('root.articles.store'), $data);

        $response->assertRedirect();

        // Assert article was created
        $this->assertDatabaseHas('articles', [
            'author_id' => auth()->id(),
            'title' => $articleData->title,
            'content' => $articleData->content,
            'excerpt' => $articleData->excerpt,
        ]);

        // Get the created article
        $article = Article::where('title', $articleData->title)->first();

        // Assert that tags were attached through the taggables pivot table
        foreach ($tagIds as $tagId) {
            $this->assertDatabaseHas('taggables', [
                'tag_id' => $tagId,
                'taggable_id' => $article->id,
                'taggable_type' => Article::class,
            ]);
        }
    }

    public function test_authorized_user_can_edit_an_article()
    {
        $this->signInAsRoot();

        $article = $this->create(Article::class);

        $response = $this->get(route('root.articles.edit', $article));
        $response->assertOk();
        $response->assertInertia(
            fn (AssertableInertia $page) => $page
                ->component('Root/Blog/Articles/Edit')
                ->has('article')
        );
    }

    public function test_authorized_user_can_update_an_article()
    {
        $this->signInAsRoot();

        $article = $this->create(Article::class);
        $articleData = $this->make(Article::class, ['author_id' => null]);

        // Create tags
        $tags = $this->create(Tag::class, [], 3);
        $tagIds = $tags->pluck('id')->toArray();

        $data = array_merge($articleData->toArray(), [
            'tags' => $tagIds,
        ]);

        $response = $this->put(route('root.articles.update', $article), $data);
        $response->assertRedirect();

        // Assert article data was updated
        $this->assertDatabaseHas('articles', [
            'id' => $article->id,
            'title' => $articleData->title,
            'content' => $articleData->content,
            'excerpt' => $articleData->excerpt,
            'author_id' => $article->author_id,
        ]);

        // Assert old tags were detached and new ones were attached
        // First, ensure the pivot entries exist for each tag
        foreach ($tagIds as $tagId) {
            $this->assertDatabaseHas('taggables', [
                'tag_id' => $tagId,
                'taggable_id' => $article->id,
                'taggable_type' => Article::class,
            ]);
        }
    }

    public function test_authorized_user_can_publish_a_draft_article()
    {
        $this->signInAsRoot();

        $article = $this->create(Article::class, ['published_at' => null]);

        $response = $this->put(route('root.articles.publish', $article));

        $response->assertRedirect();
        $this->assertDatabaseHas('articles', [
            'id' => $article->id,
            'published_at' => now(),
        ]);
    }
}