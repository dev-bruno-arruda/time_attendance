<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployeerController;
use App\Http\Middleware\RestrictEmployeerAccess;
use App\Http\Controllers\AttendanceRecordController;


Route::post('/login', [AuthController::class, 'login']);

#protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile'])->name('user.show');
    Route::post('/logout', [AuthController::class, 'logout']);
   
    //employeer routes
    Route::get('/employeers/{id}', [EmployeerController::class, 'show'])->name('employeers.show');
    Route::put('/employeers/{id}', [EmployeerController::class, 'update'])->name('employeers.update');
    Route::put('/employeers/{id}/password', [AuthController::class, 'updatePassword'])->name('updatePassword');

    //attendance routes
    Route::post('/attendance', [AttendanceRecordController::class, 'registerAttendance'])->name('attendance.register');
    
    // Only admin can access this routes
    Route::middleware([RestrictEmployeerAccess::class])->group(function () {
        Route::get('/employeers', [EmployeerController::class, 'index'])->name('employeers.index');
        Route::post('/employeers', [EmployeerController::class, 'store'])->name('employeers.store');
        Route::delete('/employeers/{id}', [EmployeerController::class, 'destroy'])->name('employeers.destroy');
        Route::get('/attendance', [AttendanceRecordController::class, 'listAttendance'])->name('attendance.list');
    });
    
});