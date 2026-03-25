<?php

use Illuminate\Support\Facades\Route;
use App\Models\Slider;
use App\Models\Setting;
use App\Http\Controllers\Api\ProductController;
use App\Models\Company;
use App\Http\Controllers\Api\ConsultationController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\TraderAuthController;
use App\Http\Controllers\Api\TraderProfileController;
use Illuminate\Http\Request;

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
Route::get('/products/best-seller', [ProductController::class, 'bestSeller']);
Route::get('/products/{slug}', [ProductController::class, 'show']);

Route::get('/company', function () {
    return Company::first();
});

Route::post('/consultations', [ConsultationController::class, 'store']);

Route::post('/reviews', [ReviewController::class, 'store']);
Route::get('/products/{id}/reviews', [ReviewController::class, 'productReviews']);

Route::get('/categories', [CategoryController::class, 'index']);

Route::post('/trader/register', [TraderAuthController::class, 'register']);
Route::post('/trader/login', [TraderAuthController::class, 'login'])->middleware('throttle:5,1');
Route::post('/trader/logout', [TraderAuthController::class, 'logout']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/trader/me', [TraderProfileController::class, 'me']);
    Route::post('/trader/profile', [TraderProfileController::class, 'update']);
});
Route::post('/trader/profile', [TraderProfileController::class, 'update']);
Route::post('/trader/refresh', [TraderAuthController::class, 'refresh']);
Route::post('/trader/change-password', [TraderAuthController::class, 'changePassword'])
    ->middleware('throttle:5,1'); // 5 lần / phút
