<?php

use App\Http\API\SignInWithEmailAndPasswordController;
use App\Http\API\SignUpWithEmailAndPasswordController;
use App\Http\Controllers\SiteController;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Route;

Route::post('/sign-up', SignUpWithEmailAndPasswordController::class);

Route::post('/sign-in', SignInWithEmailAndPasswordController::class);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/viewer', fn() => UserResource::make(\Illuminate\Support\Facades\Auth::user()));

    Route::apiResource('sites', SiteController::class);
});
