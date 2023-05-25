<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Request;


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


Route::post('/auth/login', [\App\Http\Controllers\Api\AuthController::class, 'login']);
Route::post('/auth/register', [\App\Http\Controllers\Api\AuthController::class, 'register']);

Route::middleware(['auth:sanctum'])->group(function () {

    Route::apiResources([
        'category' => \App\Http\Controllers\Api\CategoryController::class,
        'tag' => \App\Http\Controllers\Api\TagController::class,

    ]);
    Route::apiResource('shop', \App\Http\Controllers\Api\ShopController::class)->only(['store',  'update', 'destroy']);

    Route::post('/shop/{shop}/upload-logo', [\App\Http\Controllers\Api\ShopLogoController::class, 'uploadLogo']);
    Route::delete('/shop/{shop}/delete-logo', [\App\Http\Controllers\Api\ShopLogoController::class, 'deleteLogo']);
    Route::post('/shop/{shop}/upload-cover', [\App\Http\Controllers\Api\ShopCoverController::class, 'uploadCover']);
    Route::delete('/shop/{shop}/delete-cover', [\App\Http\Controllers\Api\ShopCoverController::class, 'deleteCover']);

    Route::apiResource('shop/{shop}/review', \App\Http\Controllers\Api\ReviewController::class)->only(['store', 'update', 'destroy']);

    require __DIR__ . '/api/user.php';

    require __DIR__ . '/api/admin.php';
});


Route::get('/email/verify/{id}/{hash}', [\App\Http\Controllers\Api\EmailVerificationController::class, 'verify'])->middleware(['signed', 'throttle:6,1'])->name('verification.verify');
Route::post('/email/verification-notification', [\App\Http\Controllers\Api\EmailVerificationController::class, 'sendVerificationEmail'])->middleware(['auth:sanctum', 'throttle:6,1'])->name('verification.send');

Route::apiResource('shop', \App\Http\Controllers\Api\ShopController::class)->only(['index', 'show']);


// Route::post('/forgot-password', [\App\Http\Controllers\Api\ForgotPasswordController::class, 'sendResetLinkEmail'])->middleware('guest')->name('password.email');
// Route::post('/reset-password', [\App\Http\Controllers\Api\ResetPasswordController::class, 'reset'])->middleware('guest')->name('password.update');
// Route::get('/reset-password/{token}', [\App\Http\Controllers\Api\NewPasswordController::class, 'create'])->middleware('guest')->name('password.reset');
// Route::post('/reset-password', [\App\Http\Controllers\Api\NewPasswordController::class, 'store'])->middleware('guest')->name('password.update');

// search a shop
Route::any('/search', [\App\Http\Controllers\Api\SearchShopController::class, 'search'])->name('search');
