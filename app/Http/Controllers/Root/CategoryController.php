<?php

namespace App\Http\Controllers\Root;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        return Inertia::render('Root/Categories/Index', [
            'categories' => Category::all(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Root/Categories/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
        ]);

        Category::create($request->only('name', 'slug', 'description'));

        return redirect()->route('root.categories.index')->with('success', 'Category created successfully');
    }

    public function edit(Category $category)
    {
        return Inertia::render('Root/Categories/Edit', [
            'category' => $category,
        ]);
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:categories,slug,' . $category->id,
            'description' => 'nullable|string',
        ]);

        $category->update($request->only('name', 'slug', 'description'));

        return redirect()->route('root.categories.index')->with('success', 'Category updated successfully');
    }
}
