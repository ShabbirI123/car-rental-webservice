<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CarRentalUserController;
use App\Http\Controllers\Api\CarRentalCarController;
use App\Http\Controllers\Api\CarRentalBookingController;
use App\Http\Controllers\Api\CarRentalCurrencyController;
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
    Route::post('/users/register', [CarRentalUserController::class, 'createUser']);
    //Not restful
    Route::post('/users/login', [CarRentalUserController::class, 'loginUser']);
    Route::get('/users/{id}', [CarRentalUserController::class, 'getUserData']);

    // Cars
    Route::get('/cars/{id}', [CarRentalCarController::class, 'getCarDetails']);
    Route::get('/cars', [CarRentalCarController::class, 'getAllCars']);

    // Bookings
    Route::post('/bookings', [CarRentalBookingController::class, 'createBooking']);
    Route::post('/bookings/{id}', [CarRentalBookingController::class, 'cancelBooking']);
    Route::get('/bookings/{id}', [CarRentalBookingController::class, 'getBooking']);
    Route::get('/users/{user_id}/bookings', [CarRentalBookingController::class, 'getUserBookings']);
    Route::delete('/bookings/{id}', [CarRentalBookingController::class, 'deleteBooking']);

    //Currencies
    Route::post('/currencies', [CarRentalCurrencyController::class, 'getCurrencyAmount']);
    Route::get('/currencies', [CarRentalCurrencyController::class, 'getCurrencyList']);
});
