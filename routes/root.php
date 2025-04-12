<?php

use App\Http\Middleware\EnsureRootUser;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware(['auth', 'verified', EnsureRootUser::class . ':r007'])->prefix('r007')->name('root.')->group(function () {
    Route::get('prompts', function () {
        return Inertia::render('Root/Prompts/Index');
    })->name('prompts.index');
});