<?php

namespace App\Models\Blog;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Article extends Model
{
    /** @use HasFactory<\Database\Factories\Blog\ArticleFactory> */
    use HasFactory;
    
    protected $fillable = [
        'title',
        'content',
        'excerpt',
        'published_at',
    ];
    
    protected $casts = [
        'published_at' => 'datetime',
    ];
    
    protected static function boot(): void
    {
        parent::boot();
        self::creating(function ($article) {
            $article->author_id = auth()->id();
            $article->slug = Str::slug($article->title);
            if (Article::query()->where('slug', $article->slug)->exists()) {
                $article->slug .= '-' . uniqid();
            }
        });
    }
    
    public function author() : BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
