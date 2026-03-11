<?php

use Illuminate\Support\Facades\Route;
use App\Models\Slider;
use App\Models\Setting;
use App\Http\Controllers\Api\ProductController;

Route::get('/settings', function () {
    return Setting::first();
});

Route::get('/sliders', function () {

    return Slider::where('is_active', 1)
        ->orderBy('sort_order')
        ->get();
});

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{slug}', [ProductController::class, 'show']);
