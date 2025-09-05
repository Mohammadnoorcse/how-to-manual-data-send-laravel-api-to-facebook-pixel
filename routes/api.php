<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FacebookEventController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/track-view-content', [FacebookEventController::class, 'trackViewContent']);
Route::post('/track-add-to-cart', [FacebookEventController::class, 'trackAddToCart']);
Route::post('/track-initiate-checkout', [FacebookEventController::class, 'trackInitiateCheckout']);
Route::post('/track-purchase', [FacebookEventController::class, 'trackPurchase']); // From previous project