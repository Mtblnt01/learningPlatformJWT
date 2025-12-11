<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\JwtAuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CourseController;

Route::get('/ping', function () {
    return response()->json(['message' => 'API works!'], 200);
});


//
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('register', [JwtAuthController::class, 'register']);
    Route::post('login', [JwtAuthController::class, 'login']);


    Route::middleware('auth:api')->post('logout', [JwtAuthController::class, 'logout']);
    Route::middleware('auth:api')->post('refresh', [JwtAuthController::class, 'refresh']);
});

//2. Felhasználók kezelése
Route::group([
    'middleware' => ['api', 'auth:api'],
    'prefix' => 'users'
], function ($router) {
    Route::get('me', [UserController::class, 'me']);
    Route::put('/me', [UserController::class, 'updateMe']);

    Route::get('/', [UserController::class, 'index']);
    Route::get('/{id}', [UserController::class, 'show']);
    Route::delete('/', [UserController::class, 'destroy']);

    //course controller
    Route::get('/courses', [CourseController::class, 'index']);
    Route::get('/courses/{course}', [CourseController::class, 'show']);
    Route::post('/courses/{course}/enroll', [CourseController::class, 'enroll']);
    Route::patch('/courses/{course}/completed', [CourseController::class, 'complete']);
});