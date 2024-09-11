<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoriesController;
use App\Http\Controllers\Api\CitiesController;
use App\Http\Controllers\Api\DestinationsController;
use App\Http\Controllers\Api\EnquiryDataController;
use App\Http\Controllers\Api\ToursController;
use App\Http\Controllers\Api\ImageController;
use App\Http\Controllers\Api\TourDestinationController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



// Cities routes
Route::apiResource('cities', CitiesController::class);

// Destinations routes
Route::get('/destinations', [DestinationsController::class, 'index']);
Route::get('/destinations/{id}', [DestinationsController::class, 'show']);
Route::post('/destinations', [DestinationsController::class, 'store']);
Route::put('/destinations/{id}', [DestinationsController::class, 'update']);
Route::delete('/destinations/{id}', [DestinationsController::class, 'destroy']);

// Categories routes
Route::apiResource('categories', CategoriesController::class);

// Tours routes
Route::apiResource('tours', ToursController::class);

// TourCities routes

// EnquiryData routes
Route::apiResource('enquiry-data', EnquiryDataController::class);
Route::get('/images/{imageName}', [ImageController::class, 'show'])->name('image.show');

// Tour Destinations routes
Route::prefix('tour-destinations')->group(function () {
    Route::get('/', [TourDestinationController::class, 'index']);
    Route::post('/', [TourDestinationController::class, 'store']);
    Route::get('{tourId}/{destinationId}', [TourDestinationController::class, 'show']);
    Route::put('{tourId}/{destinationId}', [TourDestinationController::class, 'update']);
    Route::delete('{tourId}/{destinationId}', [TourDestinationController::class, 'destroy']);
});

Route::get('destinations/{id}/tours', [ToursController::class, 'getToursByDestination']);
