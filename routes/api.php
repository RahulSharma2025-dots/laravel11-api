<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;

Route::post('/register', [RegisteredUserController::class, 'store']);
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/profile/update', [UserController::class, 'update']);
Route::post('/password/forgot', [UserController::class, 'sendResetLink']);
Route::post('/password-reset-link', [PasswordResetLinkController::class, 'sendResetLinkEmail']);
Route::post('/password-reset', [PasswordResetLinkController::class, 'reset']);
// Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::middleware(['auth:sanctum'])->group(function() {
    Route::get('/user/profile', [UserController::class, 'show']);
});