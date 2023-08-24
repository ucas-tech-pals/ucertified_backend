<?php

use App\Http\Controllers\API\V1\UserAuthController;
use App\Http\Controllers\API\V1\InstitutionAuthController;
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

Route::middleware('auth:sanctum')->get('/user', function () {
    return response()->json([
        'data' => auth('user')->user(),
    ]);
})->name('user');
Route::middleware('auth:sanctum')->get('/institution', function () {
    return response()->json([
        'data' => auth('institution')->user(),
    ]);
})->name('institution');

Route::group(['middleware' => ['guest']], function () {
    Route::controller(InstitutionAuthController::class)->group(function () {
        Route::post('/institution/register', 'register')->name('institution.register');
        Route::post('/institution/login', 'login')->name('institution.login');
    });

    Route::controller(UserAuthController::class)->group(function () {
        Route::post('/user/login', 'login')->name('login');
        Route::post('/user/register', 'register')->name('register');
    });
});

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/institution/logout', [InstitutionAuthController::class, 'logout']);
    Route::post('/user/logout', [UserAuthController::class, 'logout']);
});
