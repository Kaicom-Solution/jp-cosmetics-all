<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FaqRequest extends FormRequest
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
            'question' => 'required|string|max:500',
            'answer' => 'required|string',
            'order' => 'nullable|integer|min:0',
            'status' => 'nullable|boolean',
        ];

        return $rules;
    }

    public function attributes(): array
    {
        return [
            'question' => 'question',
            'answer' => 'answer',
            'order' => 'order',
            'status' => 'status',
        ];
    }

    public function messages(): array
    {
        return [
            'question.required' => 'The question field is required.',
            'question.string' => 'The question must be a string.',
            'question.max' => 'The question may not be greater than 500 characters.',
            'answer.required' => 'The answer field is required.',
            'answer.string' => 'The answer must be a string.',
            'order.integer' => 'The order must be a number.',
            'order.min' => 'The order must be at least 0.',
        ];
    }
}
