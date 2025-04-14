<?php

namespace App\Models\Concerns;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;

trait HasTags
{
    /**
     * Boot the trait.
     */
    public static function bootHasTags()
    {
        static::created(function ($model) {
            static::handleTagsAssignment($model);
        });

        static::updated(function ($model) {
            static::handleTagsAssignment($model);
        });
    }

    /**
     * Handle tags assignment from input data.
     */
    protected static function handleTagsAssignment($model)
    {
        if (request()->has('tags')) {
            $tags = request()->input('tags', []);
            if (! empty($tags)) {
                $model->setTags($tags);
            }
        }
    }

    /**
     * Get all of the tags for this model.
     */
    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    /**
     * Set the tags for this model.
     */
    public function setTags(array|Collection $tags): void
    {
        if ($tags instanceof Collection) {
            $tags = $tags->modelKeys();
        }

        $this->tags()->sync($tags);
    }

    /**
     * Determine if the model has the given tag.
     */
    public function hasTag(Tag $tag): bool
    {
        return $this->tags->contains($tag);
    }

    /**
     * Get all tag IDs for this model.
     */
    public function getTagIds(): array
    {
        return $this->tags->pluck('id')->toArray();
    }
}
