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
            $model->media->each(function (Media $media) {
                $media->delete();
            });
        });

        // Handle media uploads when model is created or updated
        static::created(function (Model $model) {
            static::handleMediaFromRequest($model);
        });

        static::updated(function (Model $model) {
            static::handleMediaFromRequest($model);
        });
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
     * Add a media file to this model.
     */
    public function addMedia(UploadedFile $file, string $collection = 'default'): Media
    {
        // Generate a unique filename
        $extension = $file->getClientOriginalExtension();
        $filename = Str::uuid().'.'.$extension;

        // Determine the path (based on model type and collection)
        $modelType = strtolower(class_basename($this));
        $relativePath = "{$modelType}/$this->id/{$collection}/{$filename}";

        // Store the file
        $path = $file->storeAs('', $relativePath, 'public');

        // Create the media record
        $media = new Media([
            'collection_name' => $collection,
            'file_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'disk' => 'public',
            'path' => $path,
            'size' => $file->getSize(),
            'is_primary' => $this->media()->where('collection_name', $collection)->doesntExist(),
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
     * Add media from a URL with attribution information.
     * Use this method specifically for URL-based images that need attribution.
     */
    public function addMediaFromUrl(string $url, string $collection = 'default', ?string $authorName = null, ?string $authorUrl = null): ?Media
    {
        if (empty($url)) {
            return null;
        }

        // Create media record for URL
        $media = new Media([
            'collection_name' => $collection,
            'file_name' => basename($url),
            'mime_type' => 'image/url', // Special type for URL-based images
            'disk' => 'external', // Indicate this is an external resource
            'path' => $url,
            'size' => 0, // Size is not applicable for URL-based images
            'is_primary' => $this->media()->where('collection_name', $collection)->doesntExist(),
        ]);

        $this->media()->save($media);

        // Add attribution if provided
        if ($authorName || $authorUrl) {
            $this->setMediaCustomProperties($media, [
                'author_name' => $authorName,
                'author_url' => $authorUrl,
            ]);
        }

        return $media;
    }

    /**
     * Handle media upload from request automatically.
     * This method is called during model creation and update.
     */
    protected static function handleMediaFromRequest(Model $model): void
    {
        $request = request();

        // Handle hero image upload
        if ($request->hasFile('hero_image') && $request->file('hero_image')->isValid()) {
            $model->addMediaFromRequest('hero_image', 'hero');
        }
        // Handle hero image URL with attribution
        elseif ($request->filled('hero_image_url')) {
            $model->addMediaFromUrl(
                $request->input('hero_image_url'),
                'hero',
                $request->input('hero_image_author_name'),
                $request->input('hero_image_author_url')
            );
        }
    }
}
