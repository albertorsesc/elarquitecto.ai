<?php

namespace App\Models;

use App\Enums\ToolBusinessModelEnum;
use App\Models\Concerns\HasCategory;
use App\Models\Concerns\HasTags;
use App\Traits\HasMedia;
use App\Traits\HasSlug;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Validator;

class Tool extends Model
{
    use HasCategory;
    use HasFactory;
    use HasMedia;
    use HasSlug;
    use HasTags;
    use HasUuid;

    protected $singleFileCollections = ['featured', 'thumbnail'];

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'description',
        'business_model',
        'featured_image',
        'gallery',
        'website_url',
        'pricing_url',
        'documentation_url',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'structured_data',
        'is_featured',
        'published_at',
    ];

    protected $casts = [
        'gallery' => 'array',
        'meta_keywords' => 'array',
        'structured_data' => 'array',
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
        'business_model' => ToolBusinessModelEnum::class,
    ];

    protected $attributes = [
        'business_model' => ToolBusinessModelEnum::FREE->value,
    ];

    protected $appends = ['featured_image_url'];

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'tool_categories');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'tool_tags');
    }

    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function getFeaturedImageUrlAttribute()
    {
        return $this->getSingleFileUrl('featured');
    }

    public function getMetaTitleAttribute($value)
    {
        return $value ?: $this->title;
    }

    public function getMetaDescriptionAttribute($value)
    {
        return $value ?: $this->excerpt;
    }

    public function getStructuredDataAttribute($value)
    {
        if ($value) {
            // If value exists, decode it from JSON if it's a string
            return is_string($value) ? json_decode($value, true) : $value;
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'SoftwareApplication',
            'name' => $this->title,
            'description' => $this->excerpt,
            'url' => route('tools.show', $this->slug),
            'applicationCategory' => 'WebApplication',
            'operatingSystem' => 'Web',
            'offers' => [
                '@type' => 'Offer',
                'price' => $this->business_model === ToolBusinessModelEnum::FREE ? '0' : null,
                'priceCurrency' => 'USD',
            ],
        ];
    }

    /**
     * Mutators for XSS protection
     */
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = strip_tags($value);
    }

    public function setExcerptAttribute($value)
    {
        $this->attributes['excerpt'] = $value ? strip_tags($value) : null;
    }

    public function setMetaTitleAttribute($value)
    {
        $this->attributes['meta_title'] = $value ? strip_tags($value) : null;
    }

    public function setMetaDescriptionAttribute($value)
    {
        $this->attributes['meta_description'] = $value ? strip_tags($value) : null;
    }

    public function setWebsiteUrlAttribute($value)
    {
        if ($value) {
            $validator = Validator::make(['url' => $value], ['url' => 'url']);
            $this->attributes['website_url'] = $validator->passes() ? $value : null;
        } else {
            $this->attributes['website_url'] = null;
        }
    }

    public function setPricingUrlAttribute($value)
    {
        if ($value) {
            $validator = Validator::make(['url' => $value], ['url' => 'url']);
            $this->attributes['pricing_url'] = $validator->passes() ? $value : null;
        } else {
            $this->attributes['pricing_url'] = null;
        }
    }

    public function setDocumentationUrlAttribute($value)
    {
        if ($value) {
            $validator = Validator::make(['url' => $value], ['url' => 'url']);
            $this->attributes['documentation_url'] = $validator->passes() ? $value : null;
        } else {
            $this->attributes['documentation_url'] = null;
        }
    }
}
