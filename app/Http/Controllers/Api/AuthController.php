<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;



class AuthController extends Controller
{
    public function register(AuthRequest $request)
    {
        $validator = $request->validated();
        $validator['password'] = Hash::make($request->password);
        $user = User::create($validator);
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'user registration successful',
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer'
        ], 200);
    }

    public function login(AuthRequest $request)
    {
        $validator = $request->validated();
        if (!Auth::attempt($validator)) {
            return response()->json([
                'message' => 'User not found'
            ], 422);
        }
        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'login successful',
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer'
        ],200);
    }
    public function logout()
    {
             Auth::user()->tokens()->delete();
        return response()->json([
            'message' => 'Logout successful'
        ],200);
    }
}
