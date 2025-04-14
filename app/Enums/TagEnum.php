<?php

namespace App\Enums;

use Illuminate\Support\Str;

enum TagEnum: string
{
    // AI Tags
    case FINE_TUNING = 'Fine-tuning';
    case PROMPT_ENGINEERING = 'Prompt Engineering';
    
    // Agent Tags
    case MULTI_AGENT = 'Multi-Agent';
    case REASONING_AGENT = 'Reasoning Agent';
    case PLANNING = 'Planning';
    
    // Machine Learning Tags
    case DEEP_LEARNING = 'Deep Learning';
    case NEURAL_NETWORKS = 'Neural Networks';
    case TRANSFORMERS = 'Transformers';
    
    // Automation Tags
    case WORKFLOW = 'Workflow';
    case SCRIPTING = 'Scripting';
    case TASK_MANAGEMENT = 'Task Management';
    
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
    
    public static function getByCategory(string $category): array
    {
        return match($category) {
            CategoryEnum::AI->value => [
                self::FINE_TUNING->value, 
                self::PROMPT_ENGINEERING->value
            ],
            CategoryEnum::AGENTS->value => [
                self::MULTI_AGENT->value, 
                self::REASONING_AGENT->value, 
                self::PLANNING->value
            ],
            CategoryEnum::MACHINE_LEARNING->value => [
                self::DEEP_LEARNING->value, 
                self::NEURAL_NETWORKS->value, 
                self::TRANSFORMERS->value
            ],
            CategoryEnum::AUTOMATION->value => [
                self::WORKFLOW->value, 
                self::SCRIPTING->value, 
                self::TASK_MANAGEMENT->value
            ],
            CategoryEnum::CONTENT_CREATION->value => [
                self::BLOG_WRITING->value,
                self::SOCIAL_MEDIA->value
            ],
            CategoryEnum::PROGRAMMING->value => [
                self::CODE_GENERATION->value,
                self::DEBUGGING->value
            ],
            default => [],
        };
    }
} 