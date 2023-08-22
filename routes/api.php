<?php

use App\Http\Controllers\FilesController;
use App\Http\Controllers\InstitutonAuthController;
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
    return $request->user();
});

Route::post('/auth/register',[InstitutonAuthController::class, 'register']);
Route::post('/auth/login',[InstitutonAuthController::class, 'login']);


Route::group(['middleware' => ['auth:sanctum']] , function(){
Route::post('/auth/logout',[InstitutonAuthController::class, 'logout']);
});

Route::post('/files', [FilesController::class, "upload"]);