<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TranslationController;
use App\Http\Controllers\AuthController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('translations', TranslationController::class);
    Route::get('export/{locale}', [TranslationController::class, 'export']);
    Route::get('search', [TranslationController::class, 'search']);
    Route::post('logout', [AuthController::class, 'logout']);
});