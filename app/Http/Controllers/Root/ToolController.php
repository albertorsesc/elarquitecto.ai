<?php

namespace App\Http\Controllers\Root;

use App\Http\Controllers\Controller;
use App\Http\Requests\ToolRequest;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Tool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class ToolController extends Controller
{
    public function index(Request $request)
    {
        $tools = Tool::query()
            ->with(['categories', 'tags', 'media'])
            ->when($request->search, function ($query, $search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%");
            })
            ->when($request->business_model, function ($query, $model) {
                $query->where('business_model', $model);
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return Inertia::render('Root/Tools/Index', [
            'tools' => $tools,
            'filters' => $request->only(['search', 'business_model']),
        ]);
    }

    public function create()
    {
        return Inertia::render('Root/Tools/Create', [
            'categories' => Category::orderBy('name')->get(),
            'tags' => Tag::orderBy('name')->get(),
        ]);
    }

    public function store(ToolRequest $request)
    {
        $data = $request->validated();

        // Remove the file from the data array as it will be handled separately
        unset($data['featured_image']);

        $tool = Tool::create($data);

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            $tool->addMediaFromRequest('featured_image', 'featured');
        }

        if ($request->has('categories')) {
            $tool->categories()->sync($request->categories);
        }

        if ($request->has('tags')) {
            $tool->tags()->sync($request->tags);
        }

        Log::info('Tool created', [
            'tool_id' => $tool->id,
            'title' => $tool->title,
            'user_id' => auth()->id(),
            'user_email' => auth()->user()->email,
        ]);

        return redirect()
            ->route('root.tools.index')
            ->with('success', 'Herramienta creada exitosamente.');
    }

    public function show(Tool $tool)
    {
        $tool->load(['categories', 'tags', 'media']);

        return Inertia::render('Root/Tools/Show', [
            'tool' => $tool,
        ]);
    }

    public function edit(Tool $tool)
    {
        $tool->load(['categories', 'tags', 'media']);

        // Append the featured_image_url accessor
        $tool->append('featured_image_url');

        return Inertia::render('Root/Tools/Edit', [
            'tool' => $tool,
            'categories' => Category::orderBy('name')->get(),
            'tags' => Tag::orderBy('name')->get(),
        ]);
    }

    public function update(ToolRequest $request, Tool $tool)
    {
        $data = $request->validated();

        // Remove the file from the data array as it will be handled separately
        unset($data['featured_image']);

        $tool->update($data);

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            $tool->addMediaFromRequest('featured_image', 'featured');
        }

        if ($request->has('categories')) {
            $tool->categories()->sync($request->categories);
        }

        if ($request->has('tags')) {
            $tool->tags()->sync($request->tags);
        }

        Log::info('Tool updated', [
            'tool_id' => $tool->id,
            'title' => $tool->title,
            'user_id' => auth()->id(),
            'user_email' => auth()->user()->email,
            'changes' => $tool->getChanges(),
        ]);

        return redirect()
            ->route('root.tools.index')
            ->with('success', 'Herramienta actualizada exitosamente.');
    }

    public function destroy(Tool $tool)
    {
        $toolData = [
            'tool_id' => $tool->id,
            'title' => $tool->title,
            'user_id' => auth()->id(),
            'user_email' => auth()->user()->email,
        ];

        $tool->delete();

        Log::warning('Tool deleted', $toolData);

        return redirect()
            ->route('root.tools.index')
            ->with('success', 'Herramienta eliminada exitosamente.');
    }
}
