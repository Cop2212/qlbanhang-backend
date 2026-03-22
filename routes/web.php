<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\SpecificationTemplateController;
use App\Http\Controllers\Admin\ProductSpecificationController;

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

            Route::post(
                'products/delete-multiple',
                [ProductController::class, 'deleteMultiple']
            )->name('products.deleteMultiple');

            Route::post(
                'products/update-status',
                [ProductController::class, 'updateStatusMultiple']
            )->name('products.updateStatusMultiple');

            //specifications
            Route::resource('specifications', SpecificationTemplateController::class);
            Route::post(
                '/products/{product}/specifications/{spec}/up',
                [ProductSpecificationController::class, 'moveUp']
            )->name('products.specifications.up');

            Route::post(
                '/products/{product}/specifications/{spec}/down',
                [ProductSpecificationController::class, 'moveDown']
            )->name('products.specifications.down');

            // Route quản lý thông số của từng sản phẩm
            Route::get('products/{product}/specifications', [ProductSpecificationController::class, 'index'])
                ->name('products.specifications.index');

            Route::post('products/{product}/specifications', [ProductSpecificationController::class, 'store'])
                ->name('products.specifications.store');

            Route::delete('products/{product}/specifications/{spec}', [ProductSpecificationController::class, 'destroy'])
                ->name('products.specifications.destroy');

            // CATEGORIES
            Route::resource('categories', CategoryController::class);

            Route::post(
                'categories/delete-multiple',
                [CategoryController::class, 'deleteMultiple']
            )->name('categories.deleteMultiple');

            // BRANDS
            Route::resource('brands', BrandController::class);

            Route::post(
                'brands/delete-multiple',
                [BrandController::class, 'deleteMultiple']
            )->name('brands.deleteMultiple');

            // SLIDER
            Route::resource('sliders', SliderController::class);

            Route::post(
                'sliders/delete-multiple',
                [SliderController::class, 'deleteMultiple']
            )->name('sliders.deleteMultiple');

            Route::post(
                'sliders/update-multiple',
                [SliderController::class, 'updateMultiple']
            )->name('sliders.updateMultiple');

            // REVIEWS
            Route::resource('reviews', ReviewController::class)
                ->only(['index', 'destroy']);

            Route::patch(
                'reviews/{review}/approve',
                [ReviewController::class, 'approve']
            )->name('reviews.approve');

            // SETTINGS
            Route::get('settings', [SettingController::class, 'index'])
                ->name('settings.index');

            Route::post('settings', [SettingController::class, 'update'])
                ->name('settings.update');

            Route::post('company/update', [CompanyController::class, 'update'])
                ->name('company.update');

            // CONSULTATIONS
            Route::resource('consultations', \App\Http\Controllers\Admin\ConsultationAdminController::class)
                ->only(['index', 'show', 'update']);
        });
    });
