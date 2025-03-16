<?php

namespace App\Http\Controllers;

use App\Models\Timeline;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class WelcomeController extends Controller
{
    public function index() : Response
    {
        $timelineItems = Timeline::with('author:id,name')->latest()->paginate(10);
        
        return Inertia::render('Welcome', [
            'items' => $timelineItems
        ]);
    }
}
