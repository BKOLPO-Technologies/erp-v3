<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Hrm\AdminController;
use App\Http\Controllers\Hrm\StaffController;
use App\Http\Controllers\Hrm\TaDaController;
use App\Http\Controllers\Hrm\LeaveApplicationController;
use App\Http\Controllers\Hrm\AttendanceController;
use App\Http\Controllers\Hrm\AttendanceActivityController;
use App\Http\Controllers\Hrm\StaffSalaryController;
use App\Http\Controllers\Hrm\ChatController;


Route::prefix('hr')->as('hrm.')->group(function () {
    /* =============== Start Hrm Route  ============= */
    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/dashboard', [AdminController::class, 'AdminDashboard'])->name('dashboard');
        Route::get('/logout', [AdminController::class, 'AdminDestroy'])->name('logout');

        // ==================== Staff Management Routes ====================
        Route::get('/staff/index', [StaffController::class, 'index'])->name('staff.list');
        Route::get('/staff/create', [StaffController::class, 'create'])->name('staff.create');
        Route::post('/staff/store', [StaffController::class, 'store'])->name('staff.store');
        Route::get('/staff/edit/{id}', [StaffController::class, 'edit'])->name('staff.edit');
        Route::post('/staff/update/{id}', [StaffController::class, 'update'])->name('staff.update');
        Route::get('/staff/show/{id}', [StaffController::class, 'show'])->name('staff.show');
        Route::get('/staff/delete/{id}', [StaffController::class, 'destroy'])->name('staff.delete');

        // ==================== TA/DA Management Routes ====================
        Route::get('/ta-da/index', [TaDaController::class, 'index'])->name('ta-da.index');
        Route::get('/ta-da/create', [TaDaController::class, 'create'])->name('ta-da.create');
        Route::post('/ta-da/store', [TaDaController::class, 'store'])->name('ta-da.store');
        Route::get('/ta-da/edit/{id}', [TaDaController::class, 'edit'])->name('ta-da.edit');
        Route::post('/ta-da/update/{id}', [TaDaController::class, 'update'])->name('ta-da.update');
        Route::get('/ta-da/view/{id}', [TaDaController::class, 'show'])->name('ta-da.show');
        Route::get('/ta-da/delete/{id}', [TaDaController::class, 'destroy'])->name('ta-da.delete');

        // ==================== Leave Management Routes ====================
        Route::get('/leaves/index', [LeaveApplicationController::class, 'index'])->name('leaves.index');
        Route::get('/leaves/create', [LeaveApplicationController::class, 'create'])->name('leaves.create');
        Route::post('/leaves/store', [LeaveApplicationController::class, 'store'])->name('leaves.store');
        Route::get('/leaves/edit/{id}', [LeaveApplicationController::class, 'edit'])->name('leaves.edit');
        Route::post('/leaves/update/{id}', [LeaveApplicationController::class, 'update'])->name('leaves.update');
        Route::get('/leaves/show/{id}', [LeaveApplicationController::class, 'show'])->name('leaves.show');
        Route::get('/leaves/delete/{id}', [LeaveApplicationController::class, 'destroy'])->name('leaves.delete');
        
        // ==================== Attendance Management Routes ====================
        Route::get('/attendance/index', [AttendanceController::class, 'index'])->name('attendance.index');
        Route::get('/attendance/create', [AttendanceController::class, 'create'])->name('attendance.create');
        Route::post('/attendance/store', [AttendanceController::class, 'store'])->name('attendance.store');
        Route::get('/attendance/edit/{id}', [AttendanceController::class, 'edit'])->name('attendance.edit');
        Route::post('/attendance/update/{id}', [AttendanceController::class, 'update'])->name('attendance.update');
        Route::get('/attendance/show/{id}', [AttendanceController::class, 'show'])->name('attendance.show');
        Route::get('/attendance/delete/{id}', [AttendanceController::class, 'destroy'])->name('attendance.delete');

        // ==================== Activity Management Routes ====================
        Route::get('/activity/index', [AttendanceActivityController::class, 'index'])->name('activity.index');
        Route::get('/activity/create', [AttendanceActivityController::class, 'create'])->name('activity.create');
        Route::post('/activity/store', [AttendanceActivityController::class, 'store'])->name('activity.store');
        Route::get('/activity/edit/{id}', [AttendanceActivityController::class, 'edit'])->name('activity.edit');
        Route::post('/activity/update/{id}', [AttendanceActivityController::class, 'update'])->name('activity.update');
        Route::get('/activity/show/{id}', [AttendanceActivityController::class, 'show'])->name('activity.show');
        Route::get('/activity/delete/{id}', [AttendanceActivityController::class, 'destroy'])->name('activity.delete');

        // ==================== Salary Management Routes ====================
        Route::prefix('salary')->as('salary.')->group(function () {
            Route::get('/', [StaffSalaryController::class, 'index'])->name('index');
            Route::get('create', [StaffSalaryController::class, 'create'])->name('create');
            Route::post('store', [StaffSalaryController::class, 'store'])->name('store');
            Route::get('/show/{id}', [StaffSalaryController::class, 'show'])->name('show');
            Route::get('/delete/{id}', [StaffSalaryController::class, 'destroy'])->name('delete');
            Route::post('/update-payment-status/{id}', [StaffSalaryController::class, 'updatePaymentStatus'])->name('updatePaymentStatus');
        });
        
    }); 

}); 