<?php

use App\Http\Controllers\Public\PromptController;
use App\Http\Controllers\SubscriberController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [WelcomeController::class, 'index'])->name('home');

// Public Prompts Routes
Route::get('prompts', [PromptController::class, 'index'])->name('prompts.index');
Route::get('prompts/{prompt}', [PromptController::class, 'show'])->name('prompts.show');

// Subscriber Routes
Route::post('subscribe', [SubscriberController::class, 'store'])->name('subscribers.store');
Route::get('subscribe/{hash}', [SubscriberController::class, 'verify'])->name('subscribers.verify');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
require __DIR__.'/root.php';
