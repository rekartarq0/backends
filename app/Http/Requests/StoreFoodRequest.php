<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFoodRequest extends FormRequest
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
        $route = $this->route()->getName();

        $rules = [
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'name_ckb' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id|numeric',
            'user_id' => 'nullable|exists:users,id|numeric',
            'price' => 'required|numeric',
            'image' => 'nullable|mimes:jpeg,png,jpg|max:2024',
            'is_available' => 'nullable|boolean',

        ];

        // Validate image only when it's present and for specific mime types and maximum size


        return $rules;
    }

    protected function failedValidation($validator)
    {
        throw new \Illuminate\Http\Exceptions\HttpResponseException(response()->json(['errors' => $validator->errors()], 422));
    }

}
