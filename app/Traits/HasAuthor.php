<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait HasAuthor
{
    /**
     * Boot the trait.
     */
    public static function bootHasAuthor(): void
    {
        static::creating(function (Model $model) {
            $model->author_id = auth()->id();
        });
    }

    /**
     * Get the author relation.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Associate an author with this model.
     */
    public function authoredBy(User $user): self
    {
        $this->author()->associate($user);

        return $this;
    }

    /**
     * Determine if this model was authored by the given user.
     */
    public function isAuthoredBy(User $user): bool
    {
        return $this->author_id === $user->id;
    }

    /**
     * Scope a query to include only models authored by the given user.
     */
    public function scopeAuthoredBy($query, User $user)
    {
        return $query->where('author_id', $user->id);
    }
}
