<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user', [AuthController::class, 'user']);
    
    // Profile & Family Routes
    Route::post('/profile/upload-kk', [\App\Http\Controllers\Api\ProfileController::class, 'uploadKk']);
    Route::get('/profile/family', [\App\Http\Controllers\Api\ProfileController::class, 'getFamilyMembers']);
    Route::post('/profile/family', [\App\Http\Controllers\Api\ProfileController::class, 'addFamilyMember']);
    Route::delete('/profile/family/{id}', [\App\Http\Controllers\Api\ProfileController::class, 'deleteFamilyMember']);

    // Admin Routes for User Management
    Route::get('/admin/users/pending', [\App\Http\Controllers\Api\AdminUserController::class, 'getPendingUsers']);
    Route::get('/admin/users/all', [\App\Http\Controllers\Api\AdminUserController::class, 'getAllUsers']);
    Route::post('/admin/users/{id}/approve', [\App\Http\Controllers\Api\AdminUserController::class, 'approveUser']);
    Route::post('/admin/users/{id}/approve-kk', [\App\Http\Controllers\Api\AdminUserController::class, 'approveKk']);
    Route::post('/admin/users/{id}/reject-kk', [\App\Http\Controllers\Api\AdminUserController::class, 'rejectKk']);
});


