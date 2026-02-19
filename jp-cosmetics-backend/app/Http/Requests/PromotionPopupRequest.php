<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PromotionPopupRequest extends FormRequest
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
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'button_text' => 'nullable|string|max:100',
            'button_url' => 'nullable|string|max:500',
            'is_live' => 'nullable|boolean',
            'status' => 'nullable|boolean',
        ];

        // If updating, make image not required
        if ($this->isMethod('post') && $this->route('id')) {
            $rules['image'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048';
        }

        return $rules;
    }

    public function attributes(): array
    {
        return [
            'title' => 'title',
            'description' => 'description',
            'image' => 'image',
            'button_text' => 'button text',
            'button_url' => 'button URL',
            'is_live' => 'live status',
            'status' => 'status',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'The title field is required.',
            'title.string' => 'The title must be a string.',
            'title.max' => 'The title may not be greater than 255 characters.',
            'image.image' => 'The file must be an image.',
            'image.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif, webp.',
            'image.max' => 'The image may not be greater than 2MB.',
            'button_url.string' => 'The button URL must be a string.',
        ];
    }
}
