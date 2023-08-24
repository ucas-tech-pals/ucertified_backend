<?php

use App\Http\Controllers\API\V1\UserAuthController;
use App\Http\Controllers\FilesController;
use App\Http\Controllers\API\V1\InstitutonAuthController;
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
        'user' => auth('user')->user(),
    ]);
})->name('user');

Route::controller(InstitutonAuthController::class)->group(function () {
    Route::post('/institution/register', 'register');
    Route::post('/institution/login', 'login');
});

Route::controller(UserAuthController::class)->group(function () {
    Route::post('/user/login', 'login');
    Route::post('/user/register', 'register');
});

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/institution/logout', [InstitutonAuthController::class, 'logout']);
    Route::post('/user/logout', [UserAuthController::class, 'logout']);
});
