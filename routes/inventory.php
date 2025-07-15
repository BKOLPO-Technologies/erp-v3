<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Inventory\AdminController;
use App\Http\Controllers\Inventory\ProductController;



Route::prefix('inventory')->as('inventory.')->group(function () {
    /* =============== Start Hrm Route  ============= */

        Route::get('/dashboard', [AdminController::class, 'AdminDashboard'])->name('dashboard');
        Route::get('/logout', [AdminController::class, 'AdminDestroy'])->name('logout');

        /* ==================== Product =================== */
        Route::prefix('product')->group(function () {
            Route::get('/', [ProductController::class, 'index'])->name('product.index');
            Route::get('/create', [ProductController::class, 'create'])->name('product.create');
            Route::post('/storeProduct', [ProductController::class, 'store'])->name('product.store');
            Route::get('/view/{id}', [ProductController::class, 'view'])->name('product.view');
            Route::get('/edit/{id}', [ProductController::class, 'edit'])->name('product.edit');
            Route::put('/update/{id}', [ProductController::class, 'update'])->name('product.update');
            Route::get('/delete/{id}', [ProductController::class, 'destrory'])->name('product.destroy');
            Route::get('/products-by-category/{categoryId}', [ProductController::class, 'getProductsByCategory'])->name('product.by.category');
        });

}); 