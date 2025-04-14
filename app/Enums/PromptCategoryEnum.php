<?php

namespace App\Enums;

use Illuminate\Support\Str;

enum PromptCategoryEnum: string
{
    case CONTENT_CREATION = 'Content Creation';
    case PROGRAMMING = 'Programming';
    
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
    
    /**
     * Get the corresponding CategoryEnum case
     */
    public function toCategoryEnum(): CategoryEnum
    {
        return match($this) {
            self::CONTENT_CREATION => CategoryEnum::CONTENT_CREATION,
            self::PROGRAMMING => CategoryEnum::PROGRAMMING,
        };
    }
} 