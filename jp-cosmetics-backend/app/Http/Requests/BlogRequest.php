<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BlogRequest extends FormRequest
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
            'category_id' => 'required|exists:blogcategories,id',
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'short_description' => 'nullable|string',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'author' => 'nullable|string|max:255',
            'is_featured' => 'nullable|boolean',
            'status' => 'nullable|boolean',
        ];

        // If updating, make image not required and exclude current id from unique slug validation
        if ($this->isMethod('post') && $this->route('id')) {
            $rules['image'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048';
            $rules['slug'] = 'nullable|string|max:255|unique:blogs,slug,' . $this->route('id');
        }

        return $rules;
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'category_id' => 'blog category',
            'title' => 'blog title',
            'slug' => 'slug',
            'short_description' => 'short description',
            'content' => 'content',
            'image' => 'image',
            'author' => 'author',
            'is_featured' => 'featured status',
            'status' => 'status',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'category_id.required' => 'Please select a blog category.',
            'category_id.exists' => 'The selected blog category is invalid.',
            'title.required' => 'The blog title field is required.',
            'title.string' => 'The blog title must be a string.',
            'title.max' => 'The blog title may not be greater than 255 characters.',
            'content.required' => 'The blog content field is required.',
            'image.image' => 'The file must be an image.',
            'image.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif, webp.',
            'image.max' => 'The image may not be greater than 2MB.',
        ];
    }
}
