<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
        
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:prompts'],
            'excerpt' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'published_at' => ['nullable', 'date'],
            'word_count' => ['required', 'integer'],
            'target_models' => ['nullable', 'array'],
            'target_models.*' => ['nullable', 'string', 'in:' . implode(',', $validModels)],
        ];
    }
}
