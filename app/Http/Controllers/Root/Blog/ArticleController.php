<?php

namespace App\Http\Controllers\Root\Blog;

use App\Http\Controllers\Controller;
use App\Models\Blog\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class ArticleController extends Controller
{
    public function index()
    {
        return Inertia::render('Root/Blog/Articles/Index', [
            'articles' => Article::with(['category', 'tags', 'media'])->get(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Root/Blog/Articles/Create', [
            'categories' => \App\Models\Category::with('tags')->get(),
            'tags' => \App\Models\Tag::all(),
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string', 'max:100000'],
            'published_at' => ['nullable', 'date'],
            'is_pinned' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'original_url' => ['nullable', 'url'],
            'hero_image' => ['nullable', 'file', 'image', 'max:5000'], // 5MB
            'tags' => ['nullable', 'array'],
            'category_id' => ['nullable', 'exists:categories,id'],
        ]);

        // If the validation fails, return the errors
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $request->only([
            'title',
            'body',
            'published_at',
            'is_pinned',
            'is_featured',
            'original_url',
        ]);

        $article = Article::create($data);

        return redirect()->route('root.blog.articles.index');
    }

    public function show(Article $article)
    {
        // increment the views count

        return Inertia::render('Root/Blog/Articles/Show', [
            'article' => $article->load(['category', 'tags', 'media']),
        ]);
    }

    public function edit(Article $article)
    {
        return Inertia::render('Root/Blog/Articles/Edit', [
            'article' => $article->load(['category', 'tags', 'media']),
            'categories' => \App\Models\Category::with('tags')->get(),
            'tags' => \App\Models\Tag::all(),
        ]);
    }

    public function update(Request $request, Article $article)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string', 'max:100000'],
            'published_at' => ['nullable', 'date'],
            'is_pinned' => ['boolean'],
            'is_featured' => ['boolean'],
            'original_url' => ['nullable', 'url'],
            'hero_image' => ['nullable', 'file', 'image', 'max:2048'], // 2MB
            'tags' => ['nullable', 'array'],
            'category_id' => ['nullable', 'exists:categories,id'],
        ]);

        $article->update($request->only([
            'title',
            'body',
            'published_at',
            'is_pinned',
            'is_featured',
            'original_url',
        ]));

        return redirect()->route('root.blog.articles.show', $article);
    }
}
