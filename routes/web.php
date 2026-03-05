<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\BrandController;

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
        });
    });
