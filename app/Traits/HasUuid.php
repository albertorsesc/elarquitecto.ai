<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait HasUuid
{
    /**
     * Boot the trait.
     */
    public static function bootHasUuid(): void
    {
        static::creating(function (Model $model) {
            if (empty($model->uuid)) {
                $model->uuid = Str::uuid()->toString();
            }
        });
    }

    /**
     * Get the model by its UUID.
     */
    public static function findByUuid(string $uuid): ?Model
    {
        return static::where('uuid', $uuid)->first();
    }

    /**
     * Get the model by its UUID or fail.
     */
    public static function findByUuidOrFail(string $uuid): Model
    {
        return static::where('uuid', $uuid)->firstOrFail();
    }
}
