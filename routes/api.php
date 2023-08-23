<?php

use App\Http\Controllers\API\V1\UserAuthController;
use App\Http\Controllers\FilesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return response()->json([
        'user' =>auth('user')->user(),
    ]);
});

Route::post(
    '/login',
    [UserAuthController::class, 'login']
)->name('login');

Route::post(
    '/register',
    [UserAuthController::class, 'register']
)->name('register');
