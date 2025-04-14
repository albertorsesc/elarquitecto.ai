<?php

namespace App\Models\Concerns;

use App\Models\Category;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasCategory
{
    /**
     * Boot the trait.
     */
    public static function bootHasCategory()
    {
        static::created(function ($model) {
            static::handleCategoryAssignment($model);
        });
        
        static::updated(function ($model) {
            static::handleCategoryAssignment($model);
        });
    }
    
    /**
     * Handle category assignment from input data.
     */
    protected static function handleCategoryAssignment($model)
    {
        if (request()->has('category_id')) {
            $category = Category::find(request()->input('category_id'));
            if ($category) {
                $model->setCategory($category);
            }
        }
    }

    /**
     * Get the category that the model belongs to.
     * 
     * We use morphToMany even though a model only belongs to one category,
     * because this provides consistency with the taggable relationship and
     * allows for potential future flexibility.
     */
    public function category(): MorphToMany
    {
        return $this->morphToMany(Category::class, 'categorizable');
    }

    /**
     * Set the category for this model.
     */
    public function setCategory(Category $category): void
    {
        $this->category()->sync([$category->id]);
    }

    /**
     * Determine if the model belongs to the given category.
     */
    public function hasCategory(Category $category): bool
    {
        return $this->category->contains($category);
    }
    
    /**
     * Get the category ID if it exists.
     */
    public function getCategoryId(): ?int
    {
        return $this->category->first()?->id;
    }
} 