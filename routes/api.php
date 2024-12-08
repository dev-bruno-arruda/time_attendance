<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployeeController;
use App\Http\Middleware\RestrictEmployeeAccess;
use App\Http\Controllers\AttendanceRecordController;


Route::post('/login', [AuthController::class, 'login']);

#protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile'])->name('user.show');
    Route::post('/logout', [AuthController::class, 'logout']);
   
    //employeer routes
    Route::get('/employees/{id}', [EmployeeController::class, 'show'])->name('employees.show');
    Route::put('/employees/{id}', [EmployeeController::class, 'update'])->name('employees.update');
    Route::put('/employees/{id}/password', [AuthController::class, 'updatePassword'])->name('updatePassword');

    //attendance routes
    Route::post('/attendance', [AttendanceRecordController::class, 'registerAttendance'])->name('attendance.register');
    
    // Only admin can access this routes
    Route::middleware([RestrictEmployeeAccess::class])->group(function () {
        Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
        Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
        Route::delete('/employees/{id}', [EmployeeController::class, 'destroy'])->name('employees.destroy');
        Route::get('/attendance', [AttendanceRecordController::class, 'listAttendance'])->name('attendance.list');
    });
    
});