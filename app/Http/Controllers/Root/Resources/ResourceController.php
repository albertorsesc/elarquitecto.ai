<?php

namespace App\Http\Controllers\Root\Resources;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class ResourceController extends Controller
{
    public function index()
    {
        return Inertia::render('Root/Resources/Index');
    }

    public function create()
    {
        return Inertia::render('Root/Resources/Create');
    }
}
