<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Inventory\AdminController;
use App\Http\Controllers\Inventory\ProductController;
use App\Http\Controllers\Inventory\TagController;
use App\Http\Controllers\Inventory\BrandController;



Route::prefix('inventory')->as('inventory.')->group(function () {
    /* =============== Start Hrm Route  ============= */

        Route::get('/dashboard', [AdminController::class, 'AdminDashboard'])->name('dashboard');
        Route::get('/logout', [AdminController::class, 'AdminDestroy'])->name('logout');

        /* ==================== Product =================== */
        Route::prefix('product')->group(function () {
            Route::get('/', [ProductController::class, 'index'])->name('product.index');
            Route::get('/create', [ProductController::class, 'create'])->name('product.create');
            Route::post('/storeProduct', [ProductController::class, 'store'])->name('product.store');
            Route::get('/view/{id}', [ProductController::class, 'show'])->name('product.view');
            Route::get('/edit/{id}', [ProductController::class, 'edit'])->name('product.edit');
            Route::put('/update/{id}', [ProductController::class, 'update'])->name('product.update');
            Route::get('/delete/{id}', [ProductController::class, 'destrory'])->name('product.destroy');
            Route::get('/products-by-category/{categoryId}', [ProductController::class, 'getProductsByCategory'])->name('product.by.category');
        });

        /* ==================== Tag =================== */
        Route::prefix('tags')->group(function () {
            Route::get('/', [TagController::class, 'index'])->name('tag.index');
            Route::get('/create', [TagController::class, 'create'])->name('tag.create');
            Route::post('/store', [TagController::class, 'store'])->name('tag.store');
            Route::post('/store2', [TagController::class, 'store2'])->name('tag.store2');
            Route::get('/show/{id}', [TagController::class, 'show'])->name('tag.show');
            Route::get('/edit/{id}', [TagController::class, 'edit'])->name('tag.edit');
            Route::put('/update/{id}', [TagController::class, 'update'])->name('tag.update');
            Route::get('/delete/{id}', [TagController::class, 'destrory'])->name('tag.destroy');
        });

        /* ==================== Brand =================== */
        Route::prefix('brands')->group(function () {
            Route::get('/', [BrandController::class, 'index'])->name('brand.index');
            Route::get('/create', [BrandController::class, 'create'])->name('brand.create');
            Route::post('/store', [BrandController::class, 'store'])->name('brand.store');
            Route::get('/show/{id}', [BrandController::class, 'show'])->name('brand.show');
            Route::get('/edit/{id}', [BrandController::class, 'edit'])->name('brand.edit');
            Route::put('/update/{id}', [BrandController::class, 'update'])->name('brand.update');
            Route::get('/delete/{id}', [BrandController::class, 'destrory'])->name('brand.destroy');
        });

}); 