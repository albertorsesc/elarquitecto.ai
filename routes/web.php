<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PromptController as PublicPromptController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SubscribeController;
use App\Http\Controllers\TimelineController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;

Route::get('/', [WelcomeController::class, 'index'])->name('home');

// Search Route
Route::get('/search', [SearchController::class, 'search'])->name('search');

// Subscriber Routes
Route::post('/subscribe', [SubscribeController::class, 'post'])->name('subscribe.post');
Route::get('/subscribe/{hash}', [SubscribeController::class, 'verify'])->name('subscribe.confirm');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('timeline', [TimelineController::class, 'index'])->name('timeline.index');

    Route::get('prompts', [PublicPromptController::class, 'index'])->name('prompts.index');
    Route::get('prompts/{prompt}', [PublicPromptController::class, 'show'])->name('prompts.show');
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

// Blog Routes
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{article}', [BlogController::class, 'show'])->name('blog.show');
Route::get('/blog/search', [BlogController::class, 'search'])->name('blog.search');
// Route::get('/blog/category/{category}', [BlogController::class, 'category'])->name('blog.category');
// Route::get('/blog/tag/{tag}', [BlogController::class, 'tag'])->name('blog.tag');
// Route::get('/blog/{article}', [BlogController::class, 'show'])->name('blog.show');

require __DIR__.'/root.php';
require __DIR__.'/settings.php';
require __DIR__.'/auth.php';