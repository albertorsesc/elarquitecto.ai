<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\Root\Blog\ArticleController;
use App\Http\Controllers\Root\Blog\Articles\Actions\PublishArticleController;
use App\Http\Controllers\Root\Prompts\PromptController;
use App\Http\Controllers\TimelineController;
use App\Http\Middleware\VerifyRootUserMiddleware;
use App\Models\Tag;
use Illuminate\Http\Request;

Route::middleware(['auth', 'verified', VerifyRootUserMiddleware::class])->prefix('root')->name('root.')->group(function () {
    Route::get('timeline/create', [TimelineController::class, 'create'])->name('timeline.create');
    Route::post('timeline', [TimelineController::class, 'store'])->name('timeline.store');
    
    Route::get('/prompts', [PromptController::class, 'index'])->name('prompts.index');
    Route::get('/prompts/create', [PromptController::class, 'create'])->name('prompts.create');
    Route::post('/prompts', [PromptController::class, 'store'])->name('prompts.store');
    Route::get('/prompts/{prompt}', [PromptController::class, 'show'])->name('prompts.show');
    Route::get('/prompts/{prompt}/edit', [PromptController::class, 'edit'])->name('prompts.edit');
    Route::put('/prompts/{prompt}', [PromptController::class, 'update'])->name('prompts.update');
    Route::delete('/prompts/{prompt}', [PromptController::class, 'destroy'])->name('prompts.destroy');
    
    Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
    Route::get('/articles/create', [ArticleController::class, 'create'])->name('articles.create');
    Route::post('/articles', [ArticleController::class, 'store'])->name('articles.store');
    Route::get('/articles/{article}', [ArticleController::class, 'show'])->name('articles.show');
    Route::get('/articles/{article}/edit', [ArticleController::class, 'edit'])->name('articles.edit');
    Route::put('/articles/{article}', [ArticleController::class, 'update'])->name('articles.update');
    Route::put('/articles/{article}/publish', PublishArticleController::class)->name('articles.publish');
    
    Route::get('/blog/categories', [BlogController::class, 'categories'])->name('blog.categories');
    Route::post('/blog/categories', [BlogController::class, 'storeCategory'])->name('blog.categories.store');
    Route::delete('/blog/categories/{category}', [BlogController::class, 'destroyCategory'])->name('blog.categories.destroy');
    Route::post('/blog/tags', [BlogController::class, 'storeTag'])->name('blog.tags.store');
    Route::delete('/blog/tags/{tag}', [BlogController::class, 'destroyTag'])->name('blog.tags.destroy');
    
    Route::post('tags', function (Request $request) {
        $request->validate([
            'name' => 'required|string|max:20|unique:tags,name',
        ]);
        
        Tag::create($request->only('name'));
        
        return back();
    })->name('tags.store');
});
