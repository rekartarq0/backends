<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class AuthRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Check if the request is for registration or login
        $route = $this->route()->getName();

        if ($route === 'register') {
            return [
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'password' => 'required|string|min:8|max:255',
                'role' => 'required', // Adjust roles as needed
                'user_id' => 'nullable|exists:users,id|numeric'
            ];
        } elseif ($route === 'login') {
            return [
                'email' => 'required|email|max:255',
                'password' => 'required|string|min:8|max:255'
            ];
        }

        return [];
    }

   
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'errors' => $validator->errors(),
            ], 422)
        );
    }
}
