<?php

namespace App\Enums;

use Illuminate\Support\Str;

enum PromptTagEnum: string
{
    // Content Creation Tags
    case BLOG_WRITING = 'Blog Writing';
    case SOCIAL_MEDIA = 'Social Media';
    
    // Programming Tags
    case CODE_GENERATION = 'Code Generation';
    case DEBUGGING = 'Debugging';
    
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
    
    public static function names(): array
    {
        return array_column(self::cases(), 'name');
    }
    
    public static function slugs(): array
    {
        return array_map(fn ($value) => Str::slug($value), self::values());
    }
    
    public function slug(): string
    {
        return Str::slug($this->value);
    }
    
    public function getTagEnum(): TagEnum
    {
        return match($this) {
            self::BLOG_WRITING => TagEnum::BLOG_WRITING,
            self::SOCIAL_MEDIA => TagEnum::SOCIAL_MEDIA,
            self::CODE_GENERATION => TagEnum::CODE_GENERATION,
            self::DEBUGGING => TagEnum::DEBUGGING,
        };
    }
    
    public static function getByCategory(string $category): array
    {
        return match($category) {
            PromptCategoryEnum::CONTENT_CREATION->value => [
                self::BLOG_WRITING->value, 
                self::SOCIAL_MEDIA->value
            ],
            PromptCategoryEnum::PROGRAMMING->value => [
                self::CODE_GENERATION->value, 
                self::DEBUGGING->value
            ],
            default => [],
        };
    }
} 