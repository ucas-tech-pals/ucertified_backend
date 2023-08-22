<?php

namespace App\Http\Controllers;

use App\Http\Requests\InstitutionAuth\LoginRequest;
use App\Http\Requests\InstitutionAuth\RegisterRequest;
use App\Models\Institution;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class InstitutonAuthController extends Controller
{
    public function register(RegisterRequest $request){
        
        $user = Institution::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        $token = $user->createToken('ucertifiedtoken')->plainTextToken;
        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }
// __________________________________________________________________________

    public function logout(Request $request){
       $request->user()->currentAccessToken()->delete();
       return response(['message' => 'Logged out'], Response::HTTP_OK);
    }
// __________________________________________________________________________

    public function login(LoginRequest $request){
        
        $user = Institution::where('email', $request->input('email'))->first();

        if (!$user || !Hash::check($request->input('password'), $user->password)) {
            return response([
                'message' => 'Bad credentials'
            ], 401);
        }

        $token = $user->createToken('ucertifiedtoken')->plainTextToken;
        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }
    
}
