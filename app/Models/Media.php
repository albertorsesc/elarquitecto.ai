<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Media extends Model
{
    use HasFactory;
    use HasUuid;

    protected $fillable = [
        'collection_name',
        'file_name',
        'mime_type',
        'disk',
        'path',
        'size',
        'custom_properties',
        'is_primary',
    ];

    protected $casts = [
        'custom_properties' => 'json',
        'is_primary' => 'boolean',
    ];

    /**
     * Get the parent mediable model.
     */
    public function mediable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the full URL to the media file.
     */
    public function getUrlAttribute(): string
    {
        return asset('storage/'.$this->path);
    }

    /**
     * Set a media item as primary.
     */
    public function markAsPrimary(): self
    {
        // Get all media for this model with the same collection
        Media::where('mediable_type', $this->mediable_type)
            ->where('mediable_id', $this->mediable_id)
            ->where('collection_name', $this->collection_name)
            ->update(['is_primary' => false]);

        $this->update(['is_primary' => true]);

        return $this;
    }
}
