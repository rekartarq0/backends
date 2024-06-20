<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TableRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return True;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'table_number' => 'required|integer',
            'seating_capacity' => 'required|integer',
            'status' => 'nullable|string|in:1,2,3',
            'user_id' => 'nullable|exists:users,id',
        ];
    }
    protected function failedValidation($validator)
    {
        throw new \Illuminate\Http\Exceptions\HttpResponseException(response()->json(['errors' => $validator->errors()], 422));
    }
}
