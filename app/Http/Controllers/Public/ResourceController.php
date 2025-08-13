<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;

class ResourceController extends Controller
{
    public function index()
    {
        return view('public.resources.index', [
            'resources' => collect([]),
        ]);
    }
}
