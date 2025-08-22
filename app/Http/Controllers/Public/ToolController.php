<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Tool;
use Illuminate\Http\Request;

class ToolController extends Controller
{
    public function index(Request $request)
    {
        $query = Tool::query()
            ->published()
            ->with(['categories', 'tags', 'media']);

        // Category filter
        if ($request->filled('categoria')) {
            $category = Category::where('slug', $request->categoria)->first();
            if ($category) {
                $query->whereHas('categories', fn ($q) => $q->where('categories.id', $category->id));
            }
        }

        // Business model filter
        if ($request->filled('modelo')) {
            $query->where('business_model', $request->modelo);
        }

        // Search filter
        if ($request->filled('buscar')) {
            $search = $request->buscar;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sort = $request->get('ordenar', 'recientes');
        $query = match ($sort) {
            'populares' => $query->orderBy('is_featured', 'desc')->orderBy('published_at', 'desc'),
            'alfabetico' => $query->orderBy('title'),
            default => $query->orderBy('published_at', 'desc'),
        };

        $tools = $query->paginate(12)->withQueryString();

        // Get categories with tool count
        $categories = Category::withCount(['tools' => function ($query) {
            $query->published();
        }])
            ->orderBy('name')
            ->get()
            ->filter(function ($category) {
                return $category->tools_count > 0;
            });

        return view('public.tools.index', compact('tools', 'categories'));
    }

    public function show($slug)
    {
        $tool = Tool::where('slug', $slug)
            ->published()
            ->with(['categories', 'tags', 'media'])
            ->firstOrFail();

        $relatedTools = Tool::query()
            ->published()
            ->with(['categories', 'tags', 'media'])
            ->where('id', '!=', $tool->id)
            ->whereHas('categories', function ($query) use ($tool) {
                $query->whereIn('categories.id', $tool->categories->pluck('id'));
            })
            ->limit(4)
            ->get();

        return view('public.tools.show', compact('tool', 'relatedTools'));
    }

    public function markdown($slug)
    {
        $tool = Tool::where('slug', $slug)
            ->published()
            ->with(['categories', 'tags'])
            ->firstOrFail();

        $markdown = "# {$tool->title}\n\n";
        $markdown .= "{$tool->excerpt}\n\n";

        if ($tool->description) {
            $markdown .= "## Descripción\n\n{$tool->description}\n\n";
        }

        if ($tool->website_url) {
            $markdown .= "## Enlaces\n\n";
            $markdown .= "- [Sitio web]({$tool->website_url})\n";
            if ($tool->pricing_url) {
                $markdown .= "- [Precios]({$tool->pricing_url})\n";
            }
            if ($tool->documentation_url) {
                $markdown .= "- [Documentación]({$tool->documentation_url})\n";
            }
            $markdown .= "\n";
        }

        $markdown .= "## Modelo de negocio\n\n{$tool->business_model->label()}\n";

        if ($tool->categories->count() > 0) {
            $markdown .= "\n## Categorías\n\n";
            foreach ($tool->categories as $category) {
                $markdown .= "- {$category->name}\n";
            }
        }

        if ($tool->tags->count() > 0) {
            $markdown .= "\n## Etiquetas\n\n";
            foreach ($tool->tags as $tag) {
                $markdown .= "- {$tag->name}\n";
            }
        }

        return response($markdown, 200)
            ->header('Content-Type', 'text/markdown; charset=UTF-8');
    }
}
