<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarRentalController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post("/todo/store",[CarRentalController::class,"store"]);

/*Route::group(["prefix"=>"CarRental"],function(){
    Route::get("/get/{id}",[CarRentalController::class,"get"]);
    Route::get("/gets",[CarRentalController::class,"gets"]);
    Route::post("/store",[CarRentalController::class,"store"]);
    Route::put("/update/{id}",[CarRentalController::class,"update"]);
    Route::delete("/delete/{id}",[CarRentalController::class,"delete"]);
});*/
