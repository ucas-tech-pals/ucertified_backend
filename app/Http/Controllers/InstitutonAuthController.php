<?php

namespace App\Http\Controllers;

use App\Models\Institution;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class InstitutonAuthController extends Controller
{
    public function register(Request $request){
        
        $fields = $request->validate([
            'name' => 'required|string',
            'email'=> 'required|string|unique:institutions,email',
            'password' =>'required|string|confirmed',
        ]);

        $user = Institution::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => Hash::make($fields['password']),
        ]);
        
        
        $token = $user->createToken('ucertifiedtoken')->plainTextToken;
        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response , 201);
    }
// __________________________________________________________________________

    public function logout(Request $request){
       $request->user()->currentAccessToken()->delete();
       return response(['message' => 'Logged out'], Response::HTTP_OK);
    }
// __________________________________________________________________________

    public function login(Request $request){
        $fields = $request->validate([
            'email'=> 'required|string',
            'password' =>'required|string',
        ]);

        $user = Institution::where('email' , $fields['email'])->first();

        if(!$user || !Hash::check($fields['password'], $user->password)){
            return response([
                'massage' => 'Bad creds'
            ] , 401);
        }
        
        $token = $user->createToken('ucertifiedtoken')->plainTextToken;
        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response , 201);
    }
    
}
