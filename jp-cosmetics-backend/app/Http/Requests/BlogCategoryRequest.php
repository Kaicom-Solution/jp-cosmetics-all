<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BlogCategoryRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|boolean',
        ];

        // If updating, exclude current id from unique slug validation
        if ($this->isMethod('post') && $this->route('id')) {
            $rules['slug'] = 'nullable|string|max:255|unique:blogcategories,slug,' . $this->route('id');
        }

        return $rules;
    }

    public function attributes(): array
    {
        return [
            'name' => 'category name',
            'slug' => 'slug',
            'description' => 'description',
            'status' => 'status',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The category name field is required.',
            'name.string' => 'The category name must be a string.',
            'name.max' => 'The category name may not be greater than 255 characters.',
        ];
    }

}
