<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ImpersonateController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'restrict.access'])->name('dashboard');

Route::get('/', [HomeController::class, 'Home'])->name('home');

Route::prefix('admin')->middleware(['auth', 'restrict.access'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /* ==================== Role and User Management =================== */
    Route::resource('roles', RoleController::class) ->middleware([
        // 'can:role-list',   
        // 'can:role-create',  
        // 'can:role-edit',   
        // 'can:role-delete',
        // 'can:role-view',
    ]);
    Route::get('roles/delete/{id}', [RoleController::class, 'destroy'])->name('roles.delete');
    Route::resource('users', UserController::class)->middleware([
        // 'can:user-list',   
        // 'can:user-create',  
        // 'can:user-edit',   
        // 'can:user-delete',   
        // 'can:user-view',   
    ]);

    Route::get('users/delete/{id}', [UserController::class, 'destroy'])->name('users.delete');

});

// login as
Route::middleware(['auth', 'role:superadmin'])->get('/impersonate/{id}', [ImpersonateController::class, 'loginAs'])->name('impersonate.login');
Route::middleware(['auth'])->get('/impersonate-leave', [ImpersonateController::class, 'leave'])->name('impersonate.leave');


require __DIR__.'/auth.php';
require __DIR__.'/accounts.php';
require __DIR__.'/hr.php';
require __DIR__.'/inventory.php';