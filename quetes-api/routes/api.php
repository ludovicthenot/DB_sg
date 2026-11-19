<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QueteController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/test', function () {
    return response()->json([
        'message' => 'Le journal de quêtes répond !'
    ]);
});

// Routes CRUD des quêtes
Route::apiResource('quetes', QueteController::class);