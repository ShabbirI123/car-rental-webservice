<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CarRentalUserController;
use App\Http\Controllers\Api\CarRentalCarController;
use App\Http\Controllers\Api\CarRentalBookingController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::prefix('car-rental/api/v1')->group(function () {
    // Users
    Route::post('/users', [CarRentalUserController::class, 'createUser']);
    Route::post('/users/login', [CarRentalUserController::class, 'authenticateUser']);
    Route::put('/users/{id}', [CarRentalUserController::class, 'modifyUserData']);
    Route::get('/users/{id}', [CarRentalUserController::class, 'getUserData']);
    Route::get('/users', [CarRentalUserController::class, 'getAllUsers']);
    Route::delete('/users/deletion/{id}', [CarRentalUserController::class, 'deleteUser']);

    // Cars
    Route::get('/cars/{id}', [CarRentalCarController::class, 'getCarDetails']);
    Route::get('/cars', [CarRentalCarController::class, 'getAllCars']);

    // Bookings
    Route::post('/bookings', [CarRentalBookingController::class, 'createBooking']);
    Route::put('/bookings/{id}', [CarRentalBookingController::class, 'updateBooking']);
    Route::get('/bookings/{id}', [CarRentalBookingController::class, 'getBooking']);
    Route::get('/users/{user_id}/bookings', [CarRentalBookingController::class, 'getUserBookings']);
    Route::delete('/bookings/{id}', [CarRentalBookingController::class, 'deleteBooking']);

    Route::middleware(['auth:sanctum'])->group(function () {
    });
});
