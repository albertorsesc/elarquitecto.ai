<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait HasSlug
{
    /**
     * Boot the trait.
     */
    public static function bootHasSlug(): void
    {
        static::creating(function (Model $model) {
            if (empty($model->slug)) {
                $model->slug = static::generateUniqueSlug($model);
            }
        });

        static::updating(function (Model $model) {
            // Generate a new slug if the title changed and the slug wasn't explicitly set
            $titleColumn = static::getTitleColumn();

            if ($model->isDirty($titleColumn) && ! $model->isDirty('slug')) {
                $model->slug = static::generateUniqueSlug($model);
            }
        });
    }

    /**
     * Get the column to use for slug generation.
     */
    protected static function getTitleColumn(): string
    {
        // Default to 'title' if no custom source is defined
        return 'title';
    }

    /**
     * Generate a unique slug for the model.
     */
    protected static function generateUniqueSlug(Model $model): string
    {
        $slug = Str::slug($model->{static::getTitleColumn()});
        $originalSlug = $slug;
        $counter = 1;

        // Check for existing slugs
        while (static::where('slug', $slug)
            ->when($model->exists, function ($query) use ($model) {
                return $query->where('id', '!=', $model->id);
            })
            ->exists()) {
            $slug = $originalSlug.'-'.$counter++;
        }

        return $slug;
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
