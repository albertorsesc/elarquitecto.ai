<?php

namespace Tests\Feature\Root;

use App\Models\Blog\Article;
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

        $articleData = $this->make(Article::class, ['author_id' => null]);

        $response = $this->post(route('root.articles.store'), $articleData->toArray());

        $response->assertRedirect();
        $this->assertDatabaseHas('articles', [
            'author_id' => auth()->id(),
            'title' => $articleData->title,
            'content' => $articleData->content,
            'excerpt' => $articleData->excerpt,
        ]);
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

        $response = $this->put(route('root.articles.update', $article), $articleData->toArray());
        $response->assertRedirect();
        $this->assertDatabaseHas('articles', [
            'id' => $article->id,
            'title' => $articleData->title,
            'content' => $articleData->content,
            'excerpt' => $articleData->excerpt,
            'author_id' => $article->author_id,
        ]);
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
