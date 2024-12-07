<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployeerController;

Route::post('/login', [AuthController::class, 'login']);

#protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile'])->name('user.show');
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/employeers', [EmployeerController::class, 'index'])->name('employeers.show');
});