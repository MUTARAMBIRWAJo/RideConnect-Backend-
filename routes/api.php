<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RideRequestController;
use App\Http\Controllers\Api\RiderResponseController;
use App\Http\Controllers\Api\PassengerController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/rides/request', [RideRequestController::class, 'store']);
    Route::post('/rides/request', [RideRequestController::class, 'store']);
    Route::post('/rides/{ride}/respond', [RiderResponseController::class, 'store']);
    Route::post('/passenger/register', [PassengerController::class, 'register']);
    // We might add more routes related to ride requests later
});

