<?php

use Illuminate\Support\Facades\Route;

use LaravelJsonApi\Laravel\Facades\JsonApiRoute;
use LaravelJsonApi\Laravel\Routing\Relationships;
use LaravelJsonApi\Laravel\Routing\ResourceRegistrar;
use LaravelJsonApi\Laravel\Http\Controllers\JsonApiController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and are assigned to the
| "api" middleware group. Make something great!
|
*/

Route::post('/v1/auth/login', [\App\Http\Controllers\Api\AuthController::class, 'login']);
Route::post('/v1/auth/register', [\App\Http\Controllers\Api\AuthController::class, 'register']);
Route::post('/v1/auth/login/google', [\App\Http\Controllers\Api\AuthController::class, 'loginWithGoogle']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('category', \App\Http\Controllers\Api\CategoryController::class);
    Route::apiResource('tag', \App\Http\Controllers\Api\TagController::class);

    Route::apiResource('shop', \App\Http\Controllers\Api\ShopController::class)->only(['store', 'update', 'destroy']);
    Route::post('/shop/{shop}/upload-logo', [\App\Http\Controllers\Api\ShopLogoController::class, 'uploadLogo']);
    Route::delete('/shop/{shop}/delete-logo', [\App\Http\Controllers\Api\ShopLogoController::class, 'deleteLogo']);
    Route::post('/shop/{shop}/upload-cover', [\App\Http\Controllers\Api\ShopCoverController::class, 'uploadCover']);
    Route::delete('/shop/{shop}/delete-cover', [\App\Http\Controllers\Api\ShopCoverController::class, 'deleteCover']);

    Route::apiResource('shop.review', \App\Http\Controllers\Api\ReviewController::class)->only(['store', 'update', 'destroy']);

    Route::put('/user/me/email', [\App\Http\Controllers\Api\UserEmailController::class, 'updateEmail']);
    Route::put('/user/me/password', [\App\Http\Controllers\Api\UserPasswordController::class, 'updatePassword']);
    Route::put('/user/me/info', [\App\Http\Controllers\Api\UserInfoController::class, 'updateInfo']);
    Route::post('/user/me/photo', [\App\Http\Controllers\Api\UserPhotoController::class, 'updatePhoto']);
    Route::delete('/user/me/photo', [\App\Http\Controllers\Api\UserPhotoController::class, 'deletePhoto']);
    Route::get('/user/me', [\App\Http\Controllers\Api\UserController::class, 'me']);

    Route::post('/shop/{shop}/favourite', [\App\Http\Controllers\Api\FavouriteController::class, 'addFavourite']);
    Route::delete('/shop/{shop}/favourite', [\App\Http\Controllers\Api\FavouriteController::class, 'removeFavourite']);
    Route::get('/user/me/favourites', [\App\Http\Controllers\Api\FavouriteController::class, 'userFavourites']);

    require __DIR__ . '/api/admin.php';
});

Route::get('/email/verify/{id}/{hash}', [\App\Http\Controllers\Api\EmailVerificationController::class, 'verify'])
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');

Route::post('/email/verification-notification', [\App\Http\Controllers\Api\EmailVerificationController::class, 'sendVerificationEmail'])
    ->middleware(['auth:sanctum', 'throttle:6,1'])
    ->name('verification.send');

Route::get('/shop', [\App\Http\Controllers\Api\ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/{shop}', [\App\Http\Controllers\Api\ShopController::class, 'show'])->name('shop.show');

Route::any('/search', [\App\Http\Controllers\Api\SearchShopController::class, 'search'])->name('search');





// JsonApiRoute::server('v1')
//     ->prefix('v1')
//     ->middleware('auth:sanctum')
//     ->resources(function (ResourceRegistrar $server) {
//         $server->resource('shops', \App\JsonApi\Shops\ShopsController::class);
//         $server->resource('categories', \App\JsonApi\Categories\CategoriesController::class);
//         $server->resource('tags', \App\JsonApi\Tags\TagsController::class);
//         $server->resource('users', \App\JsonApi\Users\UsersController::class);
//         $server->resource('reviews', \App\JsonApi\Reviews\ReviewsController::class);
//     });


JsonApiRoute::server('v1')
    ->prefix('v1')
    //->middleware('auth:sanctum')
    ->resources(function (ResourceRegistrar $server) {
        $server->resource('shops', JsonApiController::class)
            //->readOnly()
            ->only('index', 'show', 'store')
            ->relationships(function (Relationships $relations) {
                $relations->hasMany('categories');
                $relations->hasMany('tags');
                $relations->hasMany('reviews');
                $relations->hasMany('images');
                $relations->hasOne('owner');
            });

        $server->resource('categories', JsonApiController::class);
    });
