<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Requests\InstitutionAuth\LoginRequest;
use App\Models\Institution;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Auth\InstitutionAuth\InstitutionLoginRequest;
use App\Http\Requests\V1\Auth\InstitutionAuth\RegisterRequest;

class InstitutonAuthController extends Controller
{
    public function register(RegisterRequest $request){

        $user = Institution::create($request->validated());
        $token = $user->createToken('ucertifiedtoken')->plainTextToken;

        return response()->json([
            'message' => 'Registered successfully',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'data' => $user,
        ], 201);
    }
// __________________________________________________________________________

    public function login(InstitutionLoginRequest $request){
            
        $user = $request->authenticate();
        $token = $user->createToken('ucertifiedtoken')->plainTextToken;

        return response()->json([
            'message' => 'Logged in successfully',
            'token' => $token,
            'token_type' => 'Bearer',
            'data' => $user,
            ], 200);
    }
// __________________________________________________________________________

    public function logout(Request $request){
       $request->user()->currentAccessToken()->delete();
       return response(['message' => 'Logged out'], Response::HTTP_OK);
    }

}
