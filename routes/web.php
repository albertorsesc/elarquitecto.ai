<?php

use App\Http\Controllers\Public\ArticleController;
use App\Http\Controllers\Public\PromptController;
use App\Http\Controllers\Public\ToolController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\SubscriberController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [WelcomeController::class, 'index'])->name('home');

// Sitemap Routes
Route::get('sitemap.xml', [SitemapController::class, 'index'])->name('sitemap.index');
Route::get('sitemap-pages.xml', [SitemapController::class, 'pages'])->name('sitemap.pages');
Route::get('sitemap-tools.xml', [SitemapController::class, 'tools'])->name('sitemap.tools');
Route::get('sitemap-prompts.xml', [SitemapController::class, 'prompts'])->name('sitemap.prompts');
Route::get('sitemap-articles.xml', [SitemapController::class, 'articles'])->name('sitemap.articles');

// Public Prompts Routes with rate limiting
Route::middleware(['throttle:60,1'])->group(function () {
    Route::get('prompts', [PromptController::class, 'index'])->name('prompts.index');
    Route::get('prompts/{prompt}', [PromptController::class, 'show'])->name('prompts.show');
});

// Public Articles Routes with rate limiting
Route::middleware(['throttle:60,1'])->group(function () {
    Route::get('articulos', [ArticleController::class, 'index'])->name('articles.index');
    Route::get('articulos/{article}', [ArticleController::class, 'show'])->name('articles.show');
});

// Public Tools Routes with rate limiting
Route::middleware(['throttle:60,1'])->group(function () {
    Route::get('herramientas', [ToolController::class, 'index'])->name('tools.index');
    Route::get('herramientas/{slug}/markdown', [ToolController::class, 'markdown'])->name('tools.markdown');
    Route::get('herramientas/{slug}', [ToolController::class, 'show'])->name('tools.show');
});

// Subscriber Routes with stricter rate limiting for POST
Route::post('subscribe', [SubscriberController::class, 'store'])
    ->middleware('throttle:3,1')
    ->name('subscribers.store');
Route::get('subscribe/{hash}', [SubscriberController::class, 'verify'])->name('subscribers.verify');
Route::get('unsubscribe/{email}', [SubscriberController::class, 'unsubscribe'])->name('subscribers.unsubscribe');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
require __DIR__.'/root.php';
