<?php

namespace App\Http\Controllers\Root\Blog;

use App\Http\Controllers\Controller;
use App\Models\Blog\Article;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ArticleController extends Controller
{
    public function index()
    {
        return Inertia::render('Root/Blog/Articles/Index', [
            'articles' => Article::all(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Root/Blog/Articles/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string', 'max:10000'],
            'published_at' => ['nullable', 'date'],
            'is_pinned' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'original_url' => ['nullable', 'url'],
            'hero_image_url' => ['nullable', 'url'],
            'hero_image_author_name' => ['nullable', 'string'],
            'hero_image_author_url' => ['nullable', 'url'],
        ]);

        Article::create($request->only([
            'title',
            'body',
            'published_at',
            'is_pinned',
            'is_featured',
            'original_url',
            'hero_image_url',
            'hero_image_author_name',
            'hero_image_author_url',
        ]));

        return redirect()->route('root.blog.articles.index');
    }

    public function show(Article $article)
    {
        // increment the views count
        return Inertia::render('Root/Blog/Articles/Show', [
            'article' => $article,
        ]);
    }

    public function edit(Article $article)
    {
        return Inertia::render('Root/Blog/Articles/Edit', [
            'article' => $article,
        ]);
    }

    public function update(Request $request, Article $article)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string', 'max:10000'],
            'published_at' => ['nullable', 'date'],
            'is_pinned' => ['boolean'],
            'is_featured' => ['boolean'],
            'original_url' => ['nullable', 'url'],
            'hero_image_url' => ['nullable', 'url'],
            'hero_image_author_name' => ['nullable', 'string'],
            'hero_image_author_url' => ['nullable', 'url'],
        ]);

        $article->update($validated);

        return redirect()->route('root.blog.articles.show', $article);
    }
}
