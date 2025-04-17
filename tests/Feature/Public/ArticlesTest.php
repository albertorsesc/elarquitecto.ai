<?php

namespace Tests\Feature\Public;

use App\Models\Blog\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticlesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Model::unsetEventDispatcher();
    }

    public function test_articles_index_page_displays_published_articles()
    {
        // Create categories with unique names (adding test prefix)
        $category1 = $this->create(Category::class, [
            'name' => 'Index Test Category 1',
            'slug' => 'index-test-category-1',
        ]);

        $category2 = $this->create(Category::class, [
            'name' => 'Index Test Category 2',
            'slug' => 'index-test-category-2',
        ]);

        // Create published articles
        $publishedArticle1 = $this->create(Article::class, [
            'title' => 'Published Article 1',
            'slug' => 'published-article-1',
            'published_at' => now()->subDays(1),
        ]);

        // Associate with category using setCategory method
        $publishedArticle1->setCategory($category1);

        $tag1 = $this->create(Tag::class, [
            'category_id' => $category1->id,
            'name' => 'Tag 1',
            'slug' => 'tag-1',
        ]);

        $publishedArticle1->tags()->attach($tag1);

        $publishedArticle2 = $this->create(Article::class, [
            'author_id' => $this->create(User::class)->id,
            'title' => 'Published Article 2',
            'slug' => 'published-article-2',
            'published_at' => now()->subDays(2),
        ]);

        // Associate with category using setCategory method
        $publishedArticle2->setCategory($category2);

        $tag2 = $this->create(Tag::class, [
            'category_id' => $category2->id,
            'name' => 'Tag 2',
            'slug' => 'tag-2',
        ]);

        $publishedArticle2->tags()->attach($tag2);

        // Create unpublished articles (published_at is null)
        $unpublishedArticle = $this->create(Article::class, [
            'author_id' => $this->create(User::class)->id,
            'title' => 'Unpublished Article',
            'slug' => 'unpublished-article',
            'published_at' => null,
        ]);

        // Visit the articles index page
        $response = $this->get(route('articles.index'));
        $response->assertOk();

        // Assert published articles are visible
        $response->assertSee('Published Article 1');
        $response->assertSee('Published Article 2');

        // Assert unpublished articles are not visible
        $response->assertDontSee('Unpublished Article');
    }

    public function test_articles_index_shows_paginated_results()
    {
        // Create a category for all articles to use (with unique name)
        $category = $this->create(Category::class, [
            'name' => 'Pagination Test Category',
            'slug' => 'pagination-test-category',
        ]);

        $tag = $this->create(Tag::class, [
            'category_id' => $category->id,
            'name' => 'Pagination Test Tag',
            'slug' => 'pagination-test-tag',
        ]);

        // Create 8 published articles (pagination limit is 6)
        $articles = [];
        for ($i = 1; $i <= 8; $i++) {
            $article = $this->create(Article::class, [
                'title' => "Article $i",
                'slug' => "article-$i",
                'published_at' => now()->subDays($i), // Newer articles have smaller subDays values
            ]);

            $article->setCategory($category);
            $article->tags()->attach($tag);
            $articles[] = $article;
        }

        // Test first page (default)
        $response = $this->get(route('articles.index'));
        $response->assertOk();

        // First page should show the 6 newest articles (Articles 1-6)
        for ($i = 1; $i <= 6; $i++) {
            $response->assertSee("Article $i");
        }

        // First page should NOT show the 2 oldest articles (Articles 7-8)
        $response->assertDontSee('Article 7');
        $response->assertDontSee('Article 8');

        // First page should have pagination elements (testing for common pagination elements)
        $response->assertSee('Next');
        $response->assertSee('page=2');

        // Test second page
        $response = $this->get(route('articles.index', ['page' => 2]));
        $response->assertOk();

        // Second page should show the 2 oldest articles (Articles 7-8)
        $response->assertSee('Article 7');
        $response->assertSee('Article 8');

        // Second page should NOT show the 6 newest articles (Articles 1-6)
        for ($i = 1; $i <= 6; $i++) {
            $response->assertDontSee("Article $i");
        }

        // Test edge case: exactly 6 articles (should still have pagination but only one page)
        Article::whereIn('id', [$articles[6]->id, $articles[7]->id])->delete(); // Delete articles 7 and 8

        $response = $this->get(route('articles.index'));
        $response->assertOk();

        // Should show all 6 articles
        for ($i = 1; $i <= 6; $i++) {
            $response->assertSee("Article $i");
        }

        // With exactly 6 articles, pagination should be visible but should not have a "Next" link
        $response->assertDontSee('Next');

        // Test invalid page numbers
        // Laravel treats page=0 as page=1
        $response = $this->get(route('articles.index', ['page' => 0]));
        $response->assertOk();
        // Should display the same content as page 1
        for ($i = 1; $i <= 6; $i++) {
            $response->assertSee("Article $i");
        }

        // Page out of range should still return 200 OK but with no articles
        $response = $this->get(route('articles.index', ['page' => 999]));
        $response->assertOk();
        $response->assertSee('No hay artículos disponibles');
    }

    public function test_articles_can_be_filtered_by_category()
    {
        $this->markTestSkipped('This test is not implemented yet.');
    }

    public function test_articles_can_be_filtered_by_tag()
    {
        $this->markTestSkipped('This test is not implemented yet.');
    }

    public function test_articles_show_page_displays_correct_article()
    {
        $author = $this->create(User::class, [
            'name' => 'Test Author',
        ]);

        $category = $this->create(Category::class, [
            'name' => 'Show Test Category',
            'slug' => 'show-test-category',
        ]);

        $article = $this->create(Article::class, [
            'title' => 'Test Article Title',
            'body' => 'This is the test article body content.',
            'author_id' => $author->id,
            'published_at' => now()->subDay(),
            'view_count' => 0,
        ]);

        $article->category()->sync([$category->id]);

        $tag1 = $this->create(Tag::class, [
            'category_id' => $category->id,
            'name' => 'Show Test Tag 1',
            'slug' => 'show-test-tag-1',
        ]);
        $tag2 = $this->create(Tag::class, [
            'category_id' => $category->id,
            'name' => 'Show Test Tag 2',
            'slug' => 'show-test-tag-2',
        ]);
        $article->tags()->sync([$tag1->id, $tag2->id]);

        // Visit the article show page (first visitor)
        $response = $this->withSession(['viewed_articles' => []])
            ->get(route('articles.show', $article));

        $response->assertOk();

        $response->assertSee('Test Article Title');
        $response->assertSee('This is the test article body content.');
        $response->assertSee('Test Author');
        $response->assertSee('Show Test Category');
        $response->assertSee('Show Test Tag 1');
        $response->assertSee('Show Test Tag 2');

        // Reload the article from the database to check view count was incremented
        $article->refresh();
        $this->assertEquals(1, $article->view_count, 'View count should be incremented after viewing the article');

        // Visit the page again with a different session (second visitor)
        $this->withSession(['viewed_articles' => []])
            ->get(route('articles.show', $article));

        $article->refresh();
        $this->assertEquals(2, $article->view_count, 'View count should be incremented again on second view from different visitor');
    }

    public function test_article_view_count_does_not_increment_for_same_visitor()
    {
        // Create a category with a unique name for this test
        $category = $this->create(Category::class, [
            'name' => 'View Count Test Category',
            'slug' => 'view-count-test-category',
        ]);

        $article = $this->create(Article::class, [
            'title' => 'Article View Count Test',
            'body' => 'This is a test article for view count tracking.',
            'published_at' => now()->subDay(),
            'view_count' => 0,
        ]);

        // Associate with category
        $article->setCategory($category);

        // First visit - should increment view count to 1
        $this->withSession(['viewed_articles' => []])
            ->get(route('articles.show', $article));

        // Reload the article to check view count
        $article->refresh();
        $this->assertEquals(1, $article->view_count, 'View count should be 1 after first view');

        // Second visit with same session - should not increment view count
        $this->withSession(['viewed_articles' => [$article->id]])
            ->get(route('articles.show', $article));

        // Reload the article - view count should still be 1
        $article->refresh();
        $this->assertEquals(1, $article->view_count, 'View count should still be 1 after second view from same visitor');

        // Simulate view from a different visitor by using a different session
        $this->withSession(['viewed_articles' => []])
            ->get(route('articles.show', $article));

        // Reload the article - view count should now be 2
        $article->refresh();
        $this->assertEquals(2, $article->view_count, 'View count should be 2 after view from different visitor');
    }

    public function test_articles_show_returns_404_for_unpublished_article()
    {
        $this->markTestSkipped('This test is not implemented yet.');
    }

    public function test_articles_show_returns_404_for_nonexistent_article()
    {
        $this->markTestSkipped('This test is not implemented yet.');
    }

    public function test_related_articles_are_displayed_on_show_page()
    {
        $this->markTestSkipped('This test is not implemented yet.');
    }
}
