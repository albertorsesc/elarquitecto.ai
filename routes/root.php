<?php

use App\Http\Controllers\Root\PromptController;
use App\Http\Middleware\EnsureRootUser;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware(['auth', 'verified', EnsureRootUser::class . ':r007'])->prefix('r007')->name('root.')->group(function () {
    Route::get('prompts', function () {
        return Inertia::render('Root/Prompts/Index');
    })->name('prompts.index');

    Route::get('prompts/create', [PromptController::class, 'create'])->name('prompts.create');
    Route::post('prompts', [PromptController::class, 'store'])->name('prompts.store');
    Route::get('prompts/{prompt}', [PromptController::class, 'show'])->name('prompts.show');
    Route::get('prompts/{prompt}/edit', [PromptController::class, 'edit'])->name('prompts.edit');
    Route::put('prompts/{prompt}', [PromptController::class, 'update'])->name('prompts.update');
});