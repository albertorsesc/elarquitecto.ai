<?php

use App\Http\Requests\TimelineRequest;
use App\Models\Tag;
use App\Models\Timeline;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('timeline', function () {
    return Inertia::render('Timeline/Index', [
        'timelines' => Timeline::all(),
    ]);
})->name('timeline.index');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::get('timeline/create', function () {
        return Inertia::render('Timeline/Create');
    })->name('timeline.create');

    Route::post('timeline', function (TimelineRequest $request) {
        $timeline = Timeline::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'excerpt' => $request->input('excerpt'),
            'content' => $request->input('content'),
        ]);
        
        $timeline->tags()->sync($request->input('tags'));

        return to_route('timeline.index');
    })->name('timeline.store');

    Route::post('tags', function (Request $request) {
        $request->validate([
            'name' => 'required|string|max:20|unique:tags,name',
        ]);

        Tag::create($request->only('name'));

        return back();
    })->name('tags.store');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
