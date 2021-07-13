<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('/upload',[\App\Http\Controllers\UploadController::class, 'store']);
Route::post('/login',[\App\Http\Controllers\AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::get('/me',[\App\Http\Controllers\AuthController::class, 'me']);

    Route::get('/admin/uploads', [\App\Http\Controllers\AdminController::class, 'index']);
    Route::get('/admin/files/{id}', [\App\Http\Controllers\AdminController::class, 'show']);
    Route::put('/admin/files/{id}', [\App\Http\Controllers\AdminController::class, 'update']);
});
