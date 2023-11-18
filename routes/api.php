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
    Route::get('/dashbord', function () {
        return response()->json(['message' => 'This is the dashborad for all things']);
    });
});
