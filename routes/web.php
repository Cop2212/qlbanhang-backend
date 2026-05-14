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
use App\Http\Controllers\Admin\ConsultationAdminController;
use App\Http\Controllers\Admin\TraderController;
use App\Http\Controllers\Web\ProductPageController;

Route::get('/', function () {
    return redirect()->route('admin.dashboard');
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
            // Trang danh sách
            Route::get('consultations', [ConsultationAdminController::class, 'index'])
                ->name('consultations.index');

            // Update trạng thái + nội dung
            Route::patch('consultations/{id}', [ConsultationAdminController::class, 'update'])->name('consultations.update');
            // Xóa
            Route::delete('consultations/{id}', [ConsultationAdminController::class, 'destroy'])->name('consultations.destroy');
            Route::patch('consultations/{id}/admin-message', [ConsultationAdminController::class, 'updateAdminMessage'])
                ->name('consultations.updateAdminMessage');

            // TRADERS
            Route::get('traders', [TraderController::class, 'index'])
                ->name('traders.index');

            Route::post('traders/{id}/approve', [TraderController::class, 'approve'])
                ->name('traders.approve');

            Route::post('traders/{id}/reject', [TraderController::class, 'reject'])
                ->name('traders.reject');

            Route::get('traders/{id}', [TraderController::class, 'show'])
                ->name('traders.show');
        });
    });

Route::get('/products/{slug}', [ProductPageController::class, 'show']);
Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');
