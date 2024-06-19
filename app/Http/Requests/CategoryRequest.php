<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $route = $this->route()->getName();

        $rules = [
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'name_ckb' => 'required|string|max:255',
            'user_id' => 'nullable|exists:users,id|numeric',
        ];

        // Validate image only when it's present and for specific mime types and maximum size
        if ($route === 'category-update') {
            $rules['image'] = 'nullable|mimes:jpeg,png,jpg|max:2024';
        } else {
            $rules['image'] = 'nullable|mimes:jpeg,png,jpg|max:2024';
        }

        return $rules;
    }

    /**
     * Customize the response for a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     */
    protected function failedValidation($validator)
    {
        throw new \Illuminate\Http\Exceptions\HttpResponseException(response()->json(['errors' => $validator->errors()], 422));
    }
}
