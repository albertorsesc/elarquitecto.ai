<?php

namespace App\Models\Prompts;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Prompt extends Model
{
    /** @use HasFactory<\Database\Factories\Prompts\PromptFactory> */
    use HasFactory;

    protected $fillable = [
        'author_id',
        'name',
        'description',
        'content',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($prompt) {
            $prompt->author_id = auth()->id();
        });

        static::updating(function ($prompt) {
            $prompt->author_id = auth()->id();
        });
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}