<?php

namespace App\Traits;

use App\Models\Media;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait HasMedia
{
    /**
     * Boot the trait.
     */
    public static function bootHasMedia(): void
    {
        // Delete all media when model is deleted
        static::deleting(function (Model $model) {
            $model->media->each(function (Media $media) use ($model) {
                $model->deleteMedia($media);
            });
        });

        static::created(function (Model $model) {
            static::handleMediaFromRequest($model);
        });

        static::updated(function (Model $model) {
            static::handleMediaFromRequest($model);
        });
    }

    /**
     * Get the URL for a single file collection.
     * This can be used directly in model accessors, e.g.:
     *
     * public function getHeroImageUrlAttribute()
     * {
     *     return $this->getSingleFileUrl('hero');
     * }
     */
    public function getSingleFileUrl(string $collection): ?string
    {
        $media = $this->getPrimaryMedia($collection);

        return $media ? $media->url : null;
    }

    /**
     * Get all media attached to this model.
     */
    public function media(): MorphMany
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    /**
     * Get the primary media for a specific collection.
     */
    public function getPrimaryMedia(string $collection = 'default'): ?Media
    {
        return $this->media()
            ->where('collection_name', $collection)
            ->where('is_primary', true)
            ->first();
    }

    /**
     * Get all media for a specific collection.
     */
    public function getMedia(string $collection = 'default')
    {
        return $this->media()->where('collection_name', $collection)->get();
    }

    /**
     * Add media from a request.
     * This method can be called directly from a controller method
     * to handle file uploads automatically.
     */
    public function addMediaFromRequest(string $key, string $collection = 'default', array $customProperties = []): ?Media
    {
        if (! request()->hasFile($key) || ! request()->file($key)->isValid()) {
            return null;
        }

        $file = request()->file($key);
        $media = $this->addMedia($file, $collection);

        if (! empty($customProperties)) {
            $this->setMediaCustomProperties($media, $customProperties);
        }

        return $media;
    }

    /**
     * Get collections that should only have a single primary file.
     * Override this method in your model to customize behavior.
     */
    protected function getSingleFileCollections(): array
    {
        return property_exists($this, 'singleFileCollections')
            ? $this->singleFileCollections
            : ['hero', 'thumbnail', 'avatar'];
    }

    /**
     * Determine if the given collection should only have a single primary file.
     */
    protected function isSingleFileCollection(string $collection): bool
    {
        return in_array($collection, $this->getSingleFileCollections());
    }

    /**
     * Add a media file to this model.
     */
    public function addMedia(UploadedFile $file, string $collection = 'default'): Media
    {
        $extension = $file->getClientOriginalExtension();
        $filename = Str::uuid().'.'.$extension;

        $modelType = strtolower(class_basename($this));
        $relativePath = "{$modelType}/{$this->id}/{$collection}/{$filename}";

        $path = $file->storeAs('', $relativePath, 'public');

        // For collections that should only have one primary file
        // we need to delete existing files
        if ($this->isSingleFileCollection($collection)) {
            // Get all media in this collection and delete them
            $this->media()
                ->where('collection_name', $collection)
                ->get()
                ->each(function ($media) {
                    $this->deleteMedia($media);
                });
        }

        // Create the media record
        $media = new Media([
            'collection_name' => $collection,
            'file_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'disk' => 'public',
            'path' => $path,
            'size' => $file->getSize(),
            'is_primary' => true, // Always set new uploads as primary
        ]);

        $this->media()->save($media);

        return $media;
    }

    /**
     * Set the media custom properties.
     */
    public function setMediaCustomProperties(Media $media, array $properties): Media
    {
        $media->update([
            'custom_properties' => $properties,
        ]);

        return $media;
    }

    /**
     * Delete a media item.
     */
    public function deleteMedia(Media $media): bool
    {
        if ($media->mediable_id !== $this->id) {
            return false;
        }

        // Only try to delete the file if it's stored on a real disk
        // External disk is used for URLs, not actual files
        if (Storage::disk($media->disk)->exists($media->path)) {
            Storage::disk($media->disk)->delete($media->path);
        }

        return $media->delete();
    }

    /**
     * Get the URL of the primary media for a specific collection.
     */
    public function getMediaUrl(string $collection = 'default'): ?string
    {
        $media = $this->getPrimaryMedia($collection);

        return $media ? $media->url : null;
    }

    /**
     * Handle media upload from request automatically.
     * This method is called during model creation and update events.
     */
    public static function handleMediaFromRequest(Model $model): void
    {
        $request = request();

        // Define standard media collections and their associated fields
        $mediaCollections = [
            'hero' => [
                'file' => 'hero_image',
            ],
            // Add more collections here as needed with their respective field names
        ];

        // Process each media collection
        foreach ($mediaCollections as $collection => $fields) {
            // Get field name
            $fileField = $fields['file'] ?? null;

            // Handle file upload if present
            if ($fileField && $request->hasFile($fileField) && $request->file($fileField)->isValid()) {
                // A new file will automatically replace the old one in the addMedia method
                $model->addMedia($request->file($fileField), $collection);
            }
        }
    }
}
