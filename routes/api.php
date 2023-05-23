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
        'shop' => \App\Http\Controllers\Api\ShopController::class,
    ]);

    Route::post('/shop/{shop}/upload-logo', [\App\Http\Controllers\Api\ShopLogoController::class, 'uploadLogo']);
    Route::delete('/shop/{shop}/delete-logo', [\App\Http\Controllers\Api\ShopLogoController::class, 'deleteLogo']);
    Route::post('/shop/{shop}/upload-cover', [\App\Http\Controllers\Api\ShopCoverController::class, 'uploadCover']);
    Route::delete('/shop/{shop}/delete-cover', [\App\Http\Controllers\Api\ShopCoverController::class, 'deleteCover']);

    require __DIR__ . '/api/user.php';

    require __DIR__ . '/api/admin.php';
});
