<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    // Route::get('/events', [EventController::class, 'index']);
    // Route::get('/events/{event}', [EventController::class, 'show']);
    // Route::post('/events', [EventController::class, 'store']);
    // Route::put('/events/{event}', [EventController::class, 'update']);
    // Route::delete('/events/{event}', [EventController::class, 'destroy']);
    // Route::post('/events/{event}/register', [EventRegistrationController::class, 'store']);
    // Route::get('/events/{event}/registrations', [EventRegistrationController::class, 'index']);
    // Route::delete('/events/{event}/registrations/{registration}', [EventRegistrationController::class, 'destroy']);
});