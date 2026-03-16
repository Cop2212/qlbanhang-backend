<?php

use Illuminate\Support\Facades\Route;
use App\Models\Slider;
use App\Models\Setting;
use App\Http\Controllers\Api\ProductController;
use App\Models\Company;
use App\Http\Controllers\Api\ConsultationController;
use App\Http\Controllers\Api\ReviewController;

Route::get('/settings', function () {
    return Setting::first();
});

Route::get('/sliders', function () {

    return Slider::where('is_active', 1)
        ->orderBy('sort_order')
        ->get();
});

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/featured', [ProductController::class, 'featuredProducts']);
Route::get('/products/{slug}', [ProductController::class, 'show']);

Route::get('/company', function () {
    return Company::first();
});

Route::post('/consultations', [ConsultationController::class, 'store']);

Route::post('/reviews', [ReviewController::class, 'store']);
Route::get('/products/{id}/reviews', [ReviewController::class, 'productReviews']);
