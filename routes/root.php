<?php

use App\Http\Controllers\Root\Blog\ArticleController;
use App\Http\Controllers\Root\CategoryController;
use App\Http\Controllers\Root\PromptController;
use App\Http\Controllers\Root\TagController;
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
        Route::get('categories/{category}/edit', 'edit')->name('categories.edit');
        Route::put('categories/{category}', 'update')->name('categories.update');
    });

    Route::controller(TagController::class)->group(function () {
        Route::get('tags', 'index')->name('tags.index');
        Route::get('tags/create', 'create')->name('tags.create');
        Route::post('tags', 'store')->name('tags.store');
        Route::get('tags/{tag}/edit', 'edit')->name('tags.edit');
        Route::put('tags/{tag}', 'update')->name('tags.update');
    });

    Route::controller(ArticleController::class)->name('blog.')->prefix('blog')->group(function () {
        Route::get('articles', 'index')->name('articles.index');
        Route::get('articles/create', 'create')->name('articles.create');
        Route::post('articles', 'store')->name('articles.store');
        Route::get('articles/{article:slug}', 'show')->name('articles.show');
        Route::get('articles/{article:slug}/edit', 'edit')->name('articles.edit');
        Route::put('articles/{article:slug}', 'update')->name('articles.update');
    });
});
