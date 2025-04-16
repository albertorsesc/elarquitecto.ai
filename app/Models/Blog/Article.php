<?php

namespace App\Models\Blog;

use App\Traits\HasAuthor;
use App\Traits\HasSlug;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasAuthor;

    /** @use HasFactory<\Database\Factories\Blog\ArticleFactory> */
    use HasFactory;
    use HasSlug;
    use HasUuid;

    protected $fillable = [
        'title',
        'body',
        'published_at',
        'is_pinned',
        'is_featured',
        'original_url',
        'hero_image_url',
        'hero_image_author_name',
        'hero_image_author_url',
        'slug',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'published_at' => 'datetime',
        'is_pinned' => 'boolean',
        'is_featured' => 'boolean',
    ];
}
