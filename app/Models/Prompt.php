<?php

namespace App\Models;

use App\Models\Concerns\HasCategory;
use App\Models\Concerns\HasTags;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prompt extends Model
{
    /** @use HasFactory<\Database\Factories\PromptFactory> */
    use HasCategory, HasFactory, HasTags;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'image',
        'published_at',
        'word_count',
        'target_models',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'target_models' => 'array',
        'published_at' => 'datetime',
        'word_count' => 'integer',
    ];

    /**
     * Appends custom attributes to JSON representations of the model.
     */
    protected $appends = [
        'reading_time',
    ];

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Calculate the reading time for the prompt in minutes.
     */
    public function getReadingTimeAttribute(): int
    {
        if (empty($this->content)) {
            return 1;
        }

        // Average reading speed: 225 words per minute
        $words = str_word_count(strip_tags($this->content));
        $minutes = ceil($words / 225);

        return max(1, $minutes); // Ensure at least 1 minute of reading time
    }
}
