<?php

namespace App\Http\Controllers\Root\Blog;

use App\Http\Controllers\Controller;
use App\Http\Requests\Root\Blog\ArticleRequest;
use App\Models\Blog\Article;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ArticleController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Root/Blog/Articles/Index', [
            'articles' => Article::all(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Root/Blog/Articles/Create');
    }

    public function store(ArticleRequest $request): RedirectResponse
    {
        Article::create($request->validated());

        return redirect()->route('root.articles.index');
    }

    public function show(Article $article): Response
    {
        return Inertia::render('Root/Blog/Articles/Show', [
            'article' => $article,
        ]);
    }

    public function edit(Article $article): Response
    {
        return Inertia::render('Root/Blog/Articles/Edit', [
            'article' => $article,
        ]);
    }

    public function update(ArticleRequest $request, Article $article): RedirectResponse
    {
        $article->update($request->validated());

        return redirect()->route('root.articles.index');
    }
}
