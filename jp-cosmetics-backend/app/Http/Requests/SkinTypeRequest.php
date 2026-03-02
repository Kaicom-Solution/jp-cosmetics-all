<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SkinTypeRequest extends FormRequest
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
        $id = $this->route('id');

        return [
            'name'        => 'required|string|max:255|unique:skin_types,name,' . $id,
            'logo'        => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:2048',
            'description' => 'nullable|string',
            'status'      => 'nullable|boolean',
        ];
    }
}
