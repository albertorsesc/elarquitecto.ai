<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
}
