<?php

use App\Http\Requests\TimelineRequest;
use App\Models\Timeline;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('timeline', function () {
    return Inertia::render('Timeline/Index', [
        'timelines' => Timeline::all()
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
        Timeline::create($request->validated());
        
        return to_route('timeline.index');
    })->name('timeline.store');
});



require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
