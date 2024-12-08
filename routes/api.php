<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployeerController;
use App\Http\Middleware\RestrictEmployeerAccess;


Route::post('/login', [AuthController::class, 'login']);

#protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile'])->name('user.show');
    Route::post('/logout', [AuthController::class, 'logout']);
    
    Route::get('/employeers/{id}', [EmployeerController::class, 'show'])->name('employeers.show');
    Route::put('/employeers/{id}', [EmployeerController::class, 'update'])->name('employeers.update');
    
    // Only admin can access this routes
    Route::middleware([RestrictEmployeerAccess::class])->group(function () {
        Route::get('/employeers', [EmployeerController::class, 'index'])->name('employeers.index');
        Route::post('/employeers', [EmployeerController::class, 'store'])->name('employeers.store');
        Route::delete('/employeers/{id}', [EmployeerController::class, 'destroy'])->name('employeers.destroy');
    
        
    });
    
});