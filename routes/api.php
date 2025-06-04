<?php

use App\Http\Controllers\Api\ApiUserController;
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

    Route::get('/events', [ApiUserController::class, 'events' ]);

    // All Requests based on user location and blood type
    Route::get('/requests', [ApiUserController::class, 'requests']);

    // Placeholder for find donors
    Route::get('/find-donors', [ApiUserController::class, 'findDonors']);
    
    // Donation history
    Route::get('/donations', [ApiUserController::class, 'myDonations']);

    // Registered events
    Route::get('/registered-events', [ApiUserController::class, 'registeredEvents']);

    // Blood requests matching user's blood type and region
    Route::get('/blood-requests', [ApiUserController::class, 'requests']);

    // Enroll in an event
    Route::post('/events/{event}/enroll', [ApiUserController::class, 'enrollToEvent']);

    // Unenroll from an event
    Route::delete('/events/unenroll/{enroll}', [ApiUserController::class, 'unenrollfromEvent']);

    // Hospitals in user's region
    Route::get('/hospitals', [ApiUserController::class, 'getHospitals']);

    // Recipient's own blood requests
    Route::get('/my-requests', [ApiUserController::class, 'myRequests']);

    
    // Profile routes
    Route::get('/profile', [ApiUserController::class, 'viewProfile']);
    Route::put('/profile', [ApiUserController::class, 'update']);

});

// ---------- Testing Routes ---------- //

