<?php

use App\Http\Controllers\Root\CategoryController;
use App\Http\Controllers\Root\PromptController;
use App\Http\Middleware\EnsureRootUser;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', EnsureRootUser::class.':r007'])->prefix('r007')->name('root.')->group(function () {
    Route::controller(PromptController::class)->group(function () {
        Route::get('prompts', 'index')->name('prompts.index');
        Route::get('prompts/create', 'create')->name('prompts.create');
        Route::post('prompts', 'store')->name('prompts.store');
        Route::get('prompts/{prompt}', 'show')->name('prompts.show');
        Route::get('prompts/{prompt}/edit', 'edit')->name('prompts.edit');
        Route::put('prompts/{prompt}', 'update')->name('prompts.update');
        Route::delete('prompts/{prompt}', 'destroy')->name('prompts.destroy');
    });

    Route::controller(CategoryController::class)->group(function () {
        Route::get('categories', 'index')->name('categories.index');
        Route::get('categories/create', 'create')->name('categories.create');
        Route::post('categories', 'store')->name('categories.store');
        Route::get('categories/{category}', 'show')->name('categories.show');
    });
    // Route::controller(TagController::class)->group(function () {});
});
