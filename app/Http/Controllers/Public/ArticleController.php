<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Blog\Article;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::query()
            ->with(['tags:id,slug', 'category:id,slug'])
            ->whereNotNull('published_at')
            ->orderByDesc('published_at')
            ->paginate(6);

        return view('public.articles.index', [
            'articles' => $articles,
        ]);
    }

    public function show(Article $article)
    {
        if (! $article->published_at) {
            throw new ModelNotFoundException;
        }

        $viewedArticles = session()->get('viewed_articles', []);

        if (! in_array($article->id, $viewedArticles)) {
            $article->increment('view_count');

            $viewedArticles[] = $article->id;
            session()->put('viewed_articles', $viewedArticles);
        }

        $article->load(['tags', 'category', 'author']);

        $relatedArticles = Article::query()
            ->with(['tags', 'category'])
            ->whereHas('tags', function ($query) use ($article) {
                $query->whereIn('tags.id', $article->tags->pluck('id'));
            })
            ->where('id', '!=', $article->id)
            ->whereNotNull('published_at')
            ->orderByDesc('published_at')
            ->limit(3)
            ->get();

        return view('public.articles.show', [
            'article' => $article,
            'relatedArticles' => $relatedArticles,
        ]);
    }
}
