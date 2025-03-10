<?php

namespace App\Http\Controllers;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogTag;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Display a listing of the blog posts.
     */
    public function index()
    {
        $posts = BlogPost::with(['category', 'tags'])
            ->published()
            ->latest('published_at')
            ->paginate(9);

        return view('blog.index', compact('posts'));
    }

    /**
     * Display the specified blog post.
     */
    public function show(string $slug)
    {
        $post = BlogPost::with(['category', 'tags'])
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();

        return view('blog.show', compact('post'));
    }

    /**
     * Display posts by category.
     */
    public function category(string $slug)
    {
        $category = BlogCategory::where('slug', $slug)->firstOrFail();

        $posts = BlogPost::with(['category', 'tags'])
            ->published()
            ->where('category_id', $category->id)
            ->latest('published_at')
            ->paginate(9);

        return view('blog.category', compact('category', 'posts'));
    }

    /**
     * Display posts by tag.
     */
    public function tag(string $slug)
    {
        $tag = BlogTag::where('slug', $slug)->firstOrFail();

        $posts = $tag->posts()
            ->with(['category', 'tags'])
            ->published()
            ->latest('published_at')
            ->paginate(9);

        return view('blog.tag', compact('tag', 'posts'));
    }

    /**
     * Search for posts.
     */
    public function search(Request $request)
    {
        $query = $request->input('q');

        $posts = BlogPost::with(['category', 'tags'])
            ->published()
            ->where(function ($queryBuilder) use ($query) {
                $queryBuilder->where('title', 'like', "%{$query}%")
                    ->orWhere('content', 'like', "%{$query}%")
                    ->orWhere('excerpt', 'like', "%{$query}%");
            })
            ->latest('published_at')
            ->paginate(9);

        return view('blog.search', compact('posts', 'query'));
    }
}