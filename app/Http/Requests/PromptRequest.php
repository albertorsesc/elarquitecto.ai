<?php

namespace App\Http\Requests;

use App\Enums\PromptTagEnum;
use App\Models\Tag;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PromptRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $validModels = collect(config('models.models'))->flatMap(function ($models) {
            return $models;
        })->toArray();

        // Get valid prompt tag ids
        $validTagSlugs = PromptTagEnum::slugs();

        $rules = [
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'published_at' => ['nullable', 'date'],
            'word_count' => ['required', 'integer'],
            'target_models' => ['nullable', 'array'],
            'target_models.*' => ['nullable', 'string', 'in:'.implode(',', $validModels)],
            'category_id' => ['required', 'exists:categories,id'],
            'tags' => ['required', 'array', 'min:1'],
            'tags.*' => ['exists:tags,id'],
        ];

        // Get the current prompt ID if we're on an update route
        $promptId = $this->prompt ?? null;

        // Apply unique rule with ignore condition if we have a prompt ID (updating)
        if ($promptId) {
            $rules['slug'] = ['required', 'string', 'max:255', Rule::unique('prompts')->ignore($promptId)];
        } else {
            // For new prompts, enforce strict uniqueness
            $rules['slug'] = ['required', 'string', 'max:255', 'unique:prompts'];
        }

        return $rules;
    }
}
