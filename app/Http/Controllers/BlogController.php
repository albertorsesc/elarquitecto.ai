<?php

namespace App\Http\Controllers;

use App\Models\Blog\Article;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogTag;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class BlogController extends Controller
{
    /**
     * Display a listing of the blog posts.
     */
    public function index(): Response
    {
        $articles = Article::query()
            ->published()
            ->with(['author'])
            ->latest('published_at')
            ->paginate(9);

        // Define SEO data for the blog index page
        $seoData = [
            'title' => 'Blog - El Arquitecto A.I.',
            'description' => 'Artículos sobre IA, tutoriales y mejores prácticas para mantenerte actualizado.',
            'keywords' => 'inteligencia artificial, IA, blog, artículos, tutoriales',
            'ogType' => 'blog',
            'ogImage' => url('/images/blog-og.png'),
            'canonicalUrl' => route('blog.index'),
        ];

        return Inertia::render('Blog/Index', [
            'articles' => $articles,
            'seo' => $seoData,
        ]);
    }

    /**
     * Display the specified blog post.
     */
    public function show(Article $article): Response
    {
        $imageUrl = null;
        if (!empty($article->image)) {
            $imageUrl = url('storage/' . $article->image);
        } else {
            $imageUrl = url('/images/blog-og.png');
        }

        // Define SEO data for the article page
        $seoData = [
            'title' => $article->title,
            'description' => $article->excerpt ?: Str::limit(strip_tags($article->content), 160),
            'keywords' => 'inteligencia artificial, IA, blog, ' . $article->title,
            'ogType' => 'article',
            'ogImage' => $imageUrl,
            'canonicalUrl' => route('blog.show', $article),
        ];

        return Inertia::render('Blog/Show', [
            'article' => $article->load(['author']),
            'breadcrumbs' => [
                [
                    'title' => 'Home',
                    'href' => route('home'),
                ],
                [
                    'title' => 'Blog',
                    'href' => route('blog.index'),
                ],
                [
                    'title' => $article->title,
                    'href' => route('blog.show', $article),
                ],
            ],
            'seo' => $seoData,
        ]);
    }

    /**
     * Display posts by category.
     */
    public function category(BlogCategory $category): Response
    {
        $articles = Article::query()
            ->published()
            ->whereHas('category', fn ($q) => $q->where('id', $category->id))
            ->with(['author', 'category', 'tags'])
            ->latest('published_at')
            ->paginate(9);

        return Inertia::render('Blog/Index', [
            'articles' => $articles,
            'category' => $category,
            'title' => "Category: {$category->name}",
        ]);
    }

    /**
     * Display posts by tag.
     */
    public function tag(BlogTag $tag): Response
    {
        $articles = Article::query()
            ->published()
            ->whereHas('tags', fn ($q) => $q->where('id', $tag->id))
            ->with(['author', 'category', 'tags'])
            ->latest('published_at')
            ->paginate(9);

        return Inertia::render('Blog/Index', [
            'articles' => $articles,
            'tag' => $tag,
            'title' => "Tag: {$tag->name}",
        ]);
    }

    /**
     * Search for posts.
     */
    public function search(Request $request): Response
    {
        $query = $request->input('q');

        $articles = Article::query()
            ->published()
            ->where(function ($queryBuilder) use ($query) {
                $queryBuilder->where('title', 'like', "%{$query}%")
                    ->orWhere('content', 'like', "%{$query}%")
                    ->orWhere('excerpt', 'like', "%{$query}%");
            })
            ->with(['author', 'category', 'tags'])
            ->latest('published_at')
            ->paginate(9);

        return Inertia::render('Blog/Index', [
            'articles' => $articles,
            'search' => $query,
            'title' => "Search: {$query}",
        ]);
    }

    /**
     * Display a listing of the blog posts for management.
     */
    public function manage(Request $request): Response
    {
        $filters = $request->only('search');

        $query = BlogPost::with(['category', 'tags'])
            ->latest();

        if ($request->has('search') && $request->input('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%");
            });
        }

        $posts = $query->paginate(10)->withQueryString();

        return Inertia::render('Blog/Index', [
            'posts' => $posts,
            'filters' => $filters,
        ]);
    }

    /**
     * Show the form for creating a new blog post.
     */
    public function create(): Response
    {
        $categories = BlogCategory::all();
        $tags = BlogTag::all();

        return Inertia::render('Blog/Create', [
            'categories' => $categories,
            'tags' => $tags,
        ]);
    }

    /**
     * Store a newly created blog post in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string',
            'category_id' => 'nullable|exists:blog_categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:blog_tags,id',
            'featured_image' => 'nullable|image|max:2048',
            'published' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['title']);

        if ($request->hasFile('featured_image')) {
            $path = $request->file('featured_image')->store('blog/featured-images', 'public');
            $validated['featured_image'] = $path;
        }

        if ($validated['published'] ?? false) {
            $validated['published_at'] = now();
        }

        $post = BlogPost::create($validated);

        if (isset($validated['tags'])) {
            $post->tags()->attach($validated['tags']);
        }

        return redirect()->route('root.articles.index')
            ->with('success', 'Post created successfully.');
    }

    /**
     * Show the form for editing the specified blog post.
     */
    public function edit(BlogPost $post): Response
    {
        $categories = BlogCategory::all();
        $tags = BlogTag::all();

        return Inertia::render('Blog/Edit', [
            'post' => $post,
            'categories' => $categories,
            'tags' => $tags,
        ]);
    }

    /**
     * Display the categories and tags management page.
     */
    public function categories(): Response
    {
        $categories = BlogCategory::withCount('posts')->get();
        $tags = BlogTag::withCount('posts')->get();

        return Inertia::render('Blog/Categories', [
            'categories' => $categories,
            'tags' => $tags,
        ]);
    }

    /**
     * Store a newly created category in storage.
     */
    public function storeCategory(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:blog_categories,name',
        ]);

        // Generate slug
        $validated['slug'] = Str::slug($validated['name']);

        // Create category
        BlogCategory::create($validated);

        return redirect()->back()
            ->with('success', 'Category created successfully.');
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroyCategory(BlogCategory $category): RedirectResponse
    {
        Article::where('category_id', $category->id)
            ->update(['category_id' => null]);

        $category->delete();

        return redirect()->back()
            ->with('success', 'Category deleted successfully.');
    }

    /**
     * Store a newly created tag in storage.
     */
    public function storeTag(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:blog_tags,name',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        BlogTag::create($validated);

        return redirect()->back()
            ->with('success', 'Tag created successfully.');
    }

    /**
     * Remove the specified tag from storage.
     */
    public function destroyTag(Tag $tag): RedirectResponse
    {
        $tag->posts()->detach();
        $tag->delete();

        return redirect()->back()
            ->with('success', 'Tag deleted successfully.');
    }
}
