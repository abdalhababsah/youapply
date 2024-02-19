<?php
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ResetPasswordController;

//
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



// Protected Routes
Route::group(['middleware'=>['auth:sanctum']],function () {
    Route::apiResource('products', ProductController::class);

});


// Registration
Route::post('/register', [AuthController::class, 'register']);

// Login
Route::post('/login', [AuthController::class, 'login']);

// Logout
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

// Route::post('/verify-sms', [AuthController::class, 'verifySmsCode']);
Route::post('/verify-sms', [AuthController::class, 'verifySmsCode']);




// Password reset request
Route::post('/password-reset-request', [ResetPasswordController::class, 'requestPasswordReset']);

// Execute password reset
Route::post('/password-reset', [ResetPasswordController::class, 'resetPassword']);
