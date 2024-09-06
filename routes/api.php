<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoriesController;
use App\Http\Controllers\Api\CitiesController;
use App\Http\Controllers\Api\DestinationsController;
use App\Http\Controllers\Api\EnquiryDataController;
use App\Http\Controllers\Api\TourCitiesController;
use App\Http\Controllers\Api\ToursController;
use App\Http\Controllers\Api\ImageController;

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
Route::apiResource('tour-cities', TourCitiesController::class)->parameters([
    'tour-cities' => 'tourCity'
])->except(['show', 'update', 'destroy']);

Route::get('tour-cities/{tourId}/{cityId}', [TourCitiesController::class, 'show']);
Route::put('tour-cities/{tourId}/{cityId}', [TourCitiesController::class, 'update']);
Route::delete('tour-cities/{tourId}/{cityId}', [TourCitiesController::class, 'destroy']);

// EnquiryData routes
Route::apiResource('enquiry-data', EnquiryDataController::class);
Route::get('/images/{imageName}', [ImageController::class, 'show'])->name('image.show');
