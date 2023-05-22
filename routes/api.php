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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Path: routes/api.php
// Add the following route to the file:
Route::post('/login', function (Request $request) {
    // validate the incoming request data
    $request->validate([
        'email' => 'required|string',
        'password' => 'required|string',
    ]);

    // attempt to log the user in
    if (Auth::attempt($request->only('email', 'password'))) {
        // return the user object
        return response()->json(
            [
                "email" => Auth::user()->email,
                "access_token" => Auth::user()->createToken('api-token')->plainTextToken,
            ],
            200
        );
    } else {
        return response()->json([
            'error' => 'Invalid credentials'
        ], 401);
    }
})->name('login');
