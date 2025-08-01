<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Inventory\AdminController;
use App\Http\Controllers\Inventory\ProductController;
use App\Http\Controllers\Inventory\CategoryController;
use App\Http\Controllers\Inventory\UnitController;
use App\Http\Controllers\Inventory\TagController;
use App\Http\Controllers\Inventory\BrandController;
use App\Http\Controllers\Inventory\CustomerController;
use App\Http\Controllers\Inventory\VendorController;
use App\Http\Controllers\Inventory\SpecificationController;
use App\Http\Controllers\Inventory\StockInwardController;
use App\Http\Controllers\Inventory\StockOutwardController;
use App\Http\Controllers\Inventory\OrderController;
use App\Http\Controllers\Inventory\ProfileController;



Route::prefix('inventory')->as('inventory.')->middleware(['auth', 'verified', 'restrict.access'])->group(function () {
    /* =============== Start Hrm Route  ============= */
        Route::get('/dashboard', [AdminController::class, 'AdminDashboard'])->name('dashboard')->middleware('can:dashboard-menu');
        Route::get('/logout', [AdminController::class, 'AdminDestroy'])->name('logout');
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::get('/password/change', [ProfileController::class, 'changePasswordForm'])->name('password.change');
        Route::put('/password/update', [ProfileController::class, 'updatePassword'])->name('password.update');

        /* ==================== Product =================== */
        Route::prefix('product')->group(function () {
            Route::get('/', [ProductController::class, 'index'])->name('product.index');
            Route::get('/create', [ProductController::class, 'create'])->name('product.create');
            Route::post('/storeProduct', [ProductController::class, 'store'])->name('product.store');
            Route::get('/view/{id}', [ProductController::class, 'show'])->name('product.view');
            Route::get('/edit/{id}', [ProductController::class, 'edit'])->name('product.edit');
            Route::put('/update/{id}', [ProductController::class, 'update'])->name('product.update');
            Route::get('/delete/{id}', [ProductController::class, 'destroy'])->name('product.destroy');
            Route::get('/products-by-category/{categoryId}', [ProductController::class, 'getProductsByCategory'])->name('product.by.category');
            Route::post('/deleteImage', [ProductController::class, 'deleteImage'])->name('product.deleteImage');
        });

        /* ==================== Tag =================== */
        Route::prefix('categories')->group(function () {
            Route::get('/', [CategoryController::class, 'index'])->name('category.index');
            Route::get('/create', [CategoryController::class, 'create'])->name('category.create');
            Route::post('/store', [CategoryController::class, 'store'])->name('category.store');
            Route::post('/store2', [CategoryController::class, 'store2'])->name('category.store2');
            Route::get('/show/{id}', [CategoryController::class, 'show'])->name('category.show');
            Route::get('/edit/{id}', [CategoryController::class, 'edit'])->name('category.edit');
            Route::put('/update/{id}', [CategoryController::class, 'update'])->name('category.update');
            Route::get('/delete/{id}', [CategoryController::class, 'destroy'])->name('category.destroy');
        });

        /* ==================== Categories =================== */
        Route::prefix('categories')->group(function () {
            Route::get('/', [CategoryController::class, 'index'])->name('category.index');
            Route::get('/create', [CategoryController::class, 'create'])->name('category.create');
            Route::post('/store', [CategoryController::class, 'store'])->name('category.store');
            Route::post('/store2', [CategoryController::class, 'store2'])->name('category.store2');
            Route::get('/show/{id}', [CategoryController::class, 'show'])->name('category.show');
            Route::get('/edit/{id}', [CategoryController::class, 'edit'])->name('category.edit');
            Route::put('/update/{id}', [CategoryController::class, 'update'])->name('category.update');
            Route::get('/delete/{id}', [CategoryController::class, 'destroy'])->name('category.destroy');
        });

        /* ==================== Units =================== */
        Route::prefix('units')->group(function () {
            Route::get('/', [UnitController::class, 'index'])->name('unit.index');
            Route::get('/create', [UnitController::class, 'create'])->name('unit.create');
            Route::post('/store', [UnitController::class, 'store'])->name('unit.store');
            Route::post('/store2', [UnitController::class, 'store2'])->name('unit.store2');
            Route::get('/show/{id}', [UnitController::class, 'show'])->name('unit.show');
            Route::get('/edit/{id}', [UnitController::class, 'edit'])->name('unit.edit');
            Route::put('/update/{id}', [UnitController::class, 'update'])->name('unit.update');
            Route::get('/delete/{id}', [UnitController::class, 'destroy'])->name('unit.destroy');
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
            Route::get('/delete/{id}', [TagController::class, 'destroy'])->name('tag.destroy');
        });

        /* ==================== Brand =================== */
        Route::prefix('brands')->group(function () {
            Route::get('/', [BrandController::class, 'index'])->name('brand.index');
            Route::get('/create', [BrandController::class, 'create'])->name('brand.create');
            Route::post('/store', [BrandController::class, 'store'])->name('brand.store');
            Route::post('/store2', [BrandController::class, 'store2'])->name('brand.store2');
            Route::get('/show/{id}', [BrandController::class, 'show'])->name('brand.show');
            Route::get('/edit/{id}', [BrandController::class, 'edit'])->name('brand.edit');
            Route::put('/update/{id}', [BrandController::class, 'update'])->name('brand.update');
            Route::get('/delete/{id}', [BrandController::class, 'destroy'])->name('brand.destroy');
        });

        /* ==================== Customers =================== */
        Route::prefix('customers')->group(function () {
            Route::get('/', [CustomerController::class, 'index'])->name('customer.index');
            Route::get('/create', [CustomerController::class, 'create'])->name('customer.create');
            Route::post('/store', [CustomerController::class, 'store'])->name('customer.store');
            Route::get('/show/{id}', [CustomerController::class, 'show'])->name('customer.show');
            Route::post('/store2', [CustomerController::class, 'store2'])->name('customer.store2');
            Route::get('/edit/{id}', [CustomerController::class, 'edit'])->name('customer.edit');
            Route::put('/update/{id}', [CustomerController::class, 'update'])->name('customer.update');
            Route::get('/delete/{id}', [CustomerController::class, 'destroy'])->name('customer.destroy');
        });

        /* ==================== Vendors =================== */
        Route::prefix('vendors')->group(function () {
            Route::get('/', [VendorController::class, 'index'])->name('vendor.index');
            Route::get('/create', [VendorController::class, 'create'])->name('vendor.create');
            Route::post('/store', [VendorController::class, 'store'])->name('vendor.store');
            Route::get('/show/{id}', [VendorController::class, 'show'])->name('vendor.show');
            Route::post('/store2', [VendorController::class, 'store2'])->name('vendor.store2');
            Route::get('/edit/{id}', [VendorController::class, 'edit'])->name('vendor.edit');
            Route::put('/update/{id}', [VendorController::class, 'update'])->name('vendor.update');
            Route::get('/delete/{id}', [VendorController::class, 'destroy'])->name('vendor.destroy');
        });

        /* ==================== Specification =================== */
        Route::prefix('specifications')->group(function () {
            Route::get('/', [SpecificationController::class, 'index'])->name('specification.index');
            Route::get('/create', [SpecificationController::class, 'create'])->name('specification.create');
            Route::post('/store', [SpecificationController::class, 'store'])->name('specification.store');
            Route::get('/show/{id}', [SpecificationController::class, 'show'])->name('specification.show');
            Route::get('/edit/{id}', [SpecificationController::class, 'edit'])->name('specification.edit');
            Route::put('/update/{id}', [SpecificationController::class, 'update'])->name('specification.update');
            Route::get('/delete/{id}', [SpecificationController::class, 'destroy'])->name('specification.destroy');
        });

        /* ==================== Stock Inward =================== */
        Route::prefix('stockinward')->group(function () {
            Route::get('/', [StockInwardController::class, 'index'])->name('stockinward.index');
            Route::get('/create', [StockInwardController::class, 'create'])->name('stockinward.create');
            Route::post('/store', [StockInwardController::class, 'store'])->name('stockinward.store');
            Route::get('/show/{id}', [StockInwardController::class, 'show'])->name('stockinward.show');
            Route::get('/edit/{id}', [StockInwardController::class, 'edit'])->name('stockinward.edit');
            Route::put('/update/{id}', [StockInwardController::class, 'update'])->name('stockinward.update');
            Route::get('/delete/{id}', [StockInwardController::class, 'destroy'])->name('stockinward.destroy');
        });

        /* ==================== Stock Outward =================== */
        Route::prefix('stockoutward')->group(function () {
            Route::get('/', [StockOutwardController::class, 'index'])->name('stockoutward.index');
            Route::get('/create', [StockOutwardController::class, 'create'])->name('stockoutward.create');
            Route::post('/store', [StockOutwardController::class, 'store'])->name('stockoutward.store');
            Route::get('/show/{id}', [StockOutwardController::class, 'show'])->name('stockoutward.show');
            Route::get('/edit/{id}', [StockOutwardController::class, 'edit'])->name('stockoutward.edit');
            Route::put('/update/{id}', [StockOutwardController::class, 'update'])->name('stockoutward.update');
            Route::get('/delete/{id}', [StockOutwardController::class, 'destroy'])->name('stockoutward.destroy');

            // Additional AJAX routes
            Route::get('/get-orders/{customerId}', [StockOutwardController::class, 'getOrders'])->name('get.orders');
            Route::get('/get-product-details/{productId}', [StockOutwardController::class, 'getProductDetails'])->name('get.product.details');
    
        });


        /* ==================== Orders =================== */
        Route::prefix('order')->group(function () {
            Route::get('/', [OrderController::class, 'index'])->name('order.index');
            Route::get('/create', [OrderController::class, 'create'])->name('order.create');
            Route::post('/store', [OrderController::class, 'store'])->name('order.store');
            Route::get('/show/{id}', [OrderController::class, 'show'])->name('order.show');
            Route::get('/edit/{id}', [OrderController::class, 'edit'])->name('order.edit');
            Route::put('/update/{id}', [OrderController::class, 'update'])->name('order.update');
            Route::get('/delete/{id}', [OrderController::class, 'destroy'])->name('order.destroy');
            Route::post('/orders/remove-item', [OrderController::class, 'removeItem'])->name('order.removeItem');

            // Cart AJAX routes
            Route::post('/add-to-cart', [OrderController::class, 'addToCart'])->name('order.addToCart');
            Route::post('/update-cart', [OrderController::class, 'updateCart'])->name('order.updateCart');
            Route::post('/remove-from-cart', [OrderController::class, 'removeFromCart'])->name('order.removeFromCart');
            Route::post('/clear-cart', [OrderController::class, 'clearCart'])->name('order.clearCart');
        });
}); 