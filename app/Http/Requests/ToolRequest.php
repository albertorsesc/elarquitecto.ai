<?php

namespace App\Http\Requests;

use App\Enums\ToolBusinessModelEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ToolRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $toolId = $this->route('tool') ? $this->route('tool')->id : null;

        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('tools', 'slug')->ignore($toolId),
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
            ],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'business_model' => ['required', Rule::enum(ToolBusinessModelEnum::class)],
            'featured_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:5120'], // 5MB max
            'gallery' => ['nullable', 'array'],
            'gallery.*' => ['string', 'max:255'],
            'website_url' => ['nullable', 'url', 'max:255'],
            'pricing_url' => ['nullable', 'url', 'max:255'],
            'documentation_url' => ['nullable', 'url', 'max:255'],
            'meta_title' => ['nullable', 'string', 'max:60'],
            'meta_description' => ['nullable', 'string', 'max:160'],
            'meta_keywords' => ['nullable', 'array'],
            'meta_keywords.*' => ['string', 'max:50'],
            'categories' => ['nullable', 'array'],
            'categories.*' => ['exists:categories,id'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['exists:tags,id'],
            'is_featured' => ['boolean'],
            'published_at' => ['nullable', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'slug.regex' => 'El slug solo puede contener letras minúsculas, números y guiones.',
            'slug.unique' => 'Este slug ya está en uso.',
            'meta_title.max' => 'El título SEO no debe exceder 60 caracteres.',
            'meta_description.max' => 'La descripción SEO no debe exceder 160 caracteres.',
        ];
    }
}
