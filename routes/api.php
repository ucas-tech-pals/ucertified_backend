<?php

use App\Http\Controllers\API\V1\DocumentController;
use App\Http\Controllers\API\V1\UserAuthController;
use App\Http\Controllers\API\V1\InstitutionAuthController;
use App\Http\Controllers\API\V1\InstitutionController;
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

Route::middleware('auth:user')->get('/user', function () {
    return response()->json([
        'data' => auth('user')->user(),
    ]);
})->name('user');
Route::middleware('auth:institution')->get('/institution', function () {
    return response()->json([
        'data' => auth('institution')->user(),
    ]);
})->name('institution');

Route::group(['middleware' => ['guest']], function () {
    Route::controller(InstitutionAuthController::class)->group(function () {
        Route::post('/university/register', 'register')->name('university.register');
        Route::post('/university/login', 'login')->name('university.login');
    });

    Route::controller(UserAuthController::class)->group(function () {
        Route::post('/user/login', 'login')->name('login');
        Route::post('/user/register', 'register')->name('register');
    });
});

Route::post('/university/logout', [InstitutionAuthController::class, 'logout'])->middleware('auth:institution');
Route::post('/user/logout', [UserAuthController::class, 'logout'])->middleware('auth:user');

Route::apiResource('universities', InstitutionController::class,
    ['parameters' => ['universities' => 'institution']])->except(['store']);

Route::apiResource('certificates', DocumentController::class,
    ['parameters' => ['certificates' => 'document']])->except(['update', 'destroy']);

Route::post('certificates/verify', [DocumentController::class, 'verify']);