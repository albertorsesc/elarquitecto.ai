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
}
