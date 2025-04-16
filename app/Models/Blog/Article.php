<?php

namespace App\Models\Blog;

use App\Models\Concerns\HasCategory;
use App\Models\Concerns\HasTags;
use App\Models\Media;
use App\Traits\HasAuthor;
use App\Traits\HasMedia;
use App\Traits\HasSlug;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasAuthor;

    use HasCategory;

    /** @use HasFactory<\Database\Factories\Blog\ArticleFactory> */
    use HasFactory;
    use HasMedia;
    use HasSlug;
    use HasTags;
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
     * Appends custom attributes to JSON representations of the model.
     */
    protected $appends = [
        'hero_image_url',
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

    /**
     * Get the hero image URL.
     * Will prefer a media if available, fall back to hero_image_url
     */
    public function getHeroImageUrlAttribute(): ?string
    {
        $heroMedia = $this->getPrimaryMedia('hero');

        return $heroMedia ? $heroMedia->url : $this->attributes['hero_image_url'] ?? null;
    }
}
