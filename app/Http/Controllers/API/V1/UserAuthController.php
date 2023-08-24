<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;

use App\Http\Requests\V1\Auth\UserAuth\UserRegisterRequest;
use App\Http\Requests\V1\Auth\UserAuth\UserLoginRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserAuthController extends Controller
{
    public function login(UserLoginRequest $request)
    {
        $user = $request->authenticate();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Logged in successfully',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'data' => $user,
        ]);
    }

    public function register(UserRegisterRequest $request)
    {
        $user = User::create($request->validated());
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Registered successfully',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'data' => $user,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'Logged out',
        ]);
    }
}
