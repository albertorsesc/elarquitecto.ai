<?php

namespace App\Http\Controllers;

use App\Models\Blog\Article;
use App\Models\Prompt;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class WelcomeController extends Controller
{
    /**
     * Show the application welcome page.
     */
    public function index(): View
    {
        $prompts = $this->getPrompts();
        $articles = $this->getArticles();

        return view('welcome', [
            'prompts' => $prompts,
            'articles' => $articles,
        ]);
    }

    /**
     * Get prompts for the welcome page.
     */
    protected function getPrompts(): Collection
    {
        return Prompt::query()
            ->with(['tags', 'category'])
            ->whereNotNull('published_at')
            ->orderByDesc('published_at')
            ->limit(6)
            ->get();
    }

    /**
     * Get articles for the welcome page.
     */
    protected function getArticles(): Collection
    {
        return Article::query()
            ->with(['tags:id,slug', 'category:id,slug'])
            ->whereNotNull('published_at')
            ->orderByDesc('published_at')
            ->limit(6)
            ->get();
        // ->map(function ($article) {
        //     // Map tags to a simple array with 'name' and 'slug' keys
        //     $tagsArray = $article->tags->map(function ($tag) {
        //         return [
        //             $tag->slug,
        //         ];
        //     })->flatten()->toArray();

        //     // Calculate excerpt from body
        //     $excerpt = strlen($article->body) > 150
        //         ? substr(strip_tags($article->body), 0, 150) . '...'
        //         : strip_tags($article->body);

        //     return [
        //         'id' => $article->id,
        //         'type' => 'article', // Type identifier for grouping
        //         'title' => $article->title,
        //         'excerpt' => $excerpt,
        //         'content' => $excerpt, // Use excerpt as timeline content
        //         'date' => $article->published_at->isoFormat('D [de] MMM, YYYY'),
        //         'image' => $article->hero_image_url,
        //         'body' => $article->body,
        //         'reading_time' => $article->reading_time,
        //         'is_pinned' => $article->is_pinned,
        //         'is_featured' => $article->is_featured,
        //         'view_count' => $article->view_count,
        //         'published_at' => $article->published_at->format('Y-m-d H:i:s'),
        //         'url' => '#',
        //         'slug' => $article->slug,
        //         'tags' => $tagsArray,
        //         'category' => $article->category->first()->name,
        //     ];
        // });
    }
}
