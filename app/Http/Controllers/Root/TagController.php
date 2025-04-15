<?php

namespace App\Http\Controllers\Root;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TagController extends Controller
{
    public function index()
    {
        return Inertia::render('Root/Tags/Index', [
            'tags' => Tag::with('category')->get(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Root/Tags/Create', [
            'categories' => Category::all(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:tags',
            'category_id' => 'required|exists:categories,id',
        ]);

        Tag::create($request->only('name', 'slug', 'category_id'));

        return redirect()->route('root.tags.index')->with('success', 'Tag created successfully');
    }

    public function edit(Tag $tag)
    {
        return Inertia::render('Root/Tags/Edit', [
            'tag' => $tag->load('category'),
            'categories' => Category::all(),
        ]);
    }

    public function update(Request $request, Tag $tag)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:tags,slug,'.$tag->id,
            'category_id' => 'required|exists:categories,id',
        ]);

        $tag->update($request->only('name', 'slug', 'category_id'));

        return redirect()->route('root.tags.index')->with('success', 'Tag updated successfully');
    }
}
