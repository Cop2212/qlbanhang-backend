<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\SettingController;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')
    ->name('admin.')
    ->group(function () {

        // LOGIN
        Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AuthController::class, 'login']);

        // ADMIN AUTH
        Route::middleware('auth:admin')->group(function () {

            Route::get('/dashboard', [AuthController::class, 'dashboard'])
                ->name('dashboard');

            Route::post('/logout', [AuthController::class, 'logout'])
                ->name('logout');

            // PRODUCTS
            Route::resource('products', ProductController::class);

            // CATEGORIES
            Route::resource('categories', CategoryController::class);

            // BRANDS
            Route::resource('brands', BrandController::class);

            // SLIDER
            Route::resource('sliders', SliderController::class);

            // REVIEWS
            Route::resource('reviews', ReviewController::class)
                ->only(['index', 'destroy']);

            Route::patch(
                'reviews/{review}/approve',
                [ReviewController::class, 'approve']
            )
                ->name('reviews.approve');

            // SETTINGS
            Route::get('settings', [SettingController::class, 'index'])
                ->name('settings.index');

            Route::post('settings', [SettingController::class, 'update'])
                ->name('settings.update');
        });
    });
