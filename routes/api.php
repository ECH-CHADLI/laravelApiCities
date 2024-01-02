<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;

// Public Routes
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// Protected Routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('authuser', [AuthController::class, 'getUser']);
    Route::post('logout', [AuthController::class, 'logout']);

    Route::get('comments/get/{cityId}', [CommentController::class, 'index']);
    Route::post('comments/{cityId}', [CommentController::class, 'store']); // /cities/{cityId}/comments
    Route::get('comment/{commentId}', [CommentController::class, 'getComment']);/* 'CommentController@getComment' */
});

