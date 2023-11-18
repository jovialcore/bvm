<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/login', [App\Http\Controllers\Api\AuthenticationController::class, 'login'])->name('login');
Route::post('/register', [App\Http\Controllers\Api\AuthenticationController::class, 'register'])->name('register');

Route::middleware(['auth:sanctum', 'role'])->group(function () {
    Route::post('/transaction/create', [App\Http\Controllers\Api\Admin\TransactionController::class, 'createTransaction']);
    Route::put('/transaction/update/{id}', [App\Http\Controllers\Api\Admin\TransactionController::class, 'updateTransaction']);
});
