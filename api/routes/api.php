<?php

use App\Http\Controllers\GroupController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\SharedFileController;
use App\Http\Controllers\StudySessionController;
use App\Http\Controllers\UserController;

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('users', UserController::class);
    Route::apiResource('groups', GroupController::class);
    Route::apiResource('study-sessions', StudySessionController::class);
    Route::apiResource('messages', MessageController::class);
    Route::apiResource('shared-files', SharedFileController::class);
});
