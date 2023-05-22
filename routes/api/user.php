   <?php

   use Illuminate\Support\Facades\Route;

   Route::put('/user/me/email', [\App\Http\Controllers\Api\UserEmailController::class, 'updateEmail']);
   Route::put('/user/me/password', [\App\Http\Controllers\Api\UserPasswordController::class, 'updatePassword']);
   Route::put('/user/me/info', [\App\Http\Controllers\Api\UserInfoController::class, 'updateInfo']);
   Route::post('/user/me/photo', [\App\Http\Controllers\Api\UserPhotoController::class, 'updatePhoto']);
   Route::delete('/user/me/photo', [\App\Http\Controllers\Api\UserPhotoController::class, 'deletePhoto']);
   // Route::get('/user/me', [\App\Http\Controllers\Api\UserController::class, 'me']);