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
        $tags = Tag::select('id', 'name')->get();
        return Inertia::render('Timeline/Create', [
            'tags' => $tags
        ]);
    })->name('timeline.create');

    Route::post('timeline', function (TimelineRequest $request) {
        $timeline = Timeline::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'excerpt' => $request->input('excerpt'),
            'content' => $request->input('content'),
        ]);

        if ($request->has('tags')) {
            $timeline->tags()->sync($request->input('tags'));
        }

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

// Spotify routes
Route::get('/spotify/login', [App\Http\Controllers\SpotifyController::class, 'login'])->name('spotify.login');
Route::get('/auth/spotify/callback', [App\Http\Controllers\SpotifyController::class, 'callback'])->name('spotify.callback');
Route::get('/spotify/token', [App\Http\Controllers\SpotifyController::class, 'getToken'])->name('spotify.token');
Route::match(['get', 'post'], '/spotify/logout', [App\Http\Controllers\SpotifyController::class, 'logout'])->name('spotify.logout');
Route::get('/spotify/default-playlist', [App\Http\Controllers\SpotifyController::class, 'getDefaultPlaylist'])->name('spotify.default-playlist');

// Debug route - only in local environment
if (app()->environment('local')) {
    Route::get('/spotify/debug', function () {
        return response()->json([
            'client_id' => config('services.spotify.client_id'),
            'redirect_uri' => config('services.spotify.redirect'),
            'app_url' => config('app.url'),
            'routes' => [
                'login' => route('spotify.login'),
                'callback' => route('spotify.callback'),
                'token' => route('spotify.token'),
                'logout' => route('spotify.logout'),
            ]
        ]);
    })->name('spotify.debug');
}

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';