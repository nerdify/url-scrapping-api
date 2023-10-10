<?php

use App\Http\API\SignInWithEmailAndPasswordController;
use App\Http\API\SignUpWithEmailAndPasswordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->get('/viewer', function (Request $request) {
    return $request->user();
});

Route::post('/sign-up', SignUpWithEmailAndPasswordController::class);

Route::post('/sign-in', SignInWithEmailAndPasswordController::class);
