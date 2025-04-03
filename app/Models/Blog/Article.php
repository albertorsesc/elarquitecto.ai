<?php

namespace App\Models\Blog;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Scout\Searchable;

class Article extends Model
{
    /** @use HasFactory<\Database\Factories\Blog\ArticleFactory> */
    use HasFactory;

    use Searchable;

    protected $fillable = [
        'title',
        'content',
        'excerpt',
        'image',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    /**
     * The attributes that should be excluded from mass assignment.
     *
     * @var array<string>
     */
    protected $guarded = ['image'];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($article) {
            $article->author_id = Auth::id();
            $article->slug = Str::slug($article->title);
            if (Article::query()->where('slug', $article->slug)->exists()) {
                $article->slug .= '-' . uniqid();
            }

            if (request()->hasFile('image')) {
                $article->image = request()->file('image')->store('blog/images', 'public');
            }
        });

        static::updating(function ($article) {
            if (request()->hasFile('image')) {
                if ($article->image) {
                    Storage::disk('public')->delete($article->image);
                }
                $article->image = request()->file('image')->store('blog/images', 'public');
            }

            if (! request()->hasFile('image') && array_key_exists('image', $article->getDirty())) {
                $article->image = $article->getOriginal('image');
            }
        });

        static::deleting(function ($article) {
            // Clean up image when article is deleted
            if ($article->image) {
                Storage::disk('public')->delete($article->image);
            }
        });
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->whereNotNull('published_at');
    }

    /**
     * Get the name of the index associated with the model.
     */
    public function searchableAs(): string
    {
        return 'articles_index';
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array<string, mixed>
     */
    public function toSearchableArray(): array
    {
        $array = [
            'id' => $this->id,
            'title' => $this->title,
            'excerpt' => $this->excerpt,
        ];

        return $array;
    }

    /**
     * Determine if the model should be searchable.
     */
    public function shouldBeSearchable(): bool
    {
        return (bool) $this->published_at;
    }
}
