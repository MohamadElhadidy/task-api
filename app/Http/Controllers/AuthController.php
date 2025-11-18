<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\SuccessResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {

        $user = User::create($request->validated());
        if (!$user) {
            return new ErrorResource('User Registration Failed', null, 422);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return new SuccessResource(
            'User Registered Successfully',
            [
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer'
            ],
            201
        );
    }


    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return new ErrorResource('The provided credentials are incorrect.', null, 422);
        }

        $token = $user->createToken($request->email)->plainTextToken;

        return new SuccessResource(
            'User Logged In Successfully',
            [
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer'
            ],
        );
    }


    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return new SuccessResource('Logged out successfully');
    }

}
