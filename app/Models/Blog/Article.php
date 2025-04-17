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

    /**
     * Define which media collections should only have a single file.
     * This property is used by the HasMedia trait.
     */
    protected $singleFileCollections = ['hero'];

    protected $fillable = [
        'title',
        'body',
        'published_at',
        'is_pinned',
        'is_featured',
        'original_url',
        'slug',
    ];

    /**
     * Appends custom attributes to JSON representations of the model.
     */
    protected $appends = [
        'hero_image_url',
        'reading_time',
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
     * This uses the getSingleFileUrl method from the HasMedia trait.
     */
    public function getHeroImageUrlAttribute(): ?string
    {
        return $this->getSingleFileUrl('hero');
    }

    /**
     * Calculate the reading time for the article in minutes.
     */
    public function getReadingTimeAttribute(): int
    {
        if (empty($this->body)) {
            return 1;
        }

        // Average reading speed: 225 words per minute
        $words = str_word_count(strip_tags($this->body));
        $minutes = ceil($words / 225);

        return max(1, $minutes); // Ensure at least 1 minute of reading time
    }
}
