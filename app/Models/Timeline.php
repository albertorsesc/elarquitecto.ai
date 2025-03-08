<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Str;

class Timeline extends Model
{
    /** @use HasFactory<\Database\Factories\TimelineFactory> */
    use HasFactory;

    protected $fillable = [
        'author_id',
        'title',
        'slug',
        'description',
        'excerpt',
        'content',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($timeline) {
            $timeline->slug = Str::slug($timeline->title);
            $timeline->author_id = auth()->id();
        });
    }
    
    public function author() : BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }
    
    public function tags() : BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'timeline_tags');
    }
}
