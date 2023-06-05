<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

/**
 * I write this code if you want to use the google login, in web not api
 * check routes/web.php
 */
class LoginController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }
    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->user();
        dd($googleUser);
        // Check if the user already exists in the database
        $user = User::where('email', $googleUser->getEmail())->first();


        if (!$user) {
            // Create a new user record if it doesn't exist
            $user = new User();
            $user->email = $googleUser->getEmail();
            $user->first_name = $googleUser->getGivenName();
            $user->last_name = $googleUser->getFamilyName();
            $user->photo = $googleUser->getAvatar();

            $user->google_id = $googleUser->getId();
            $user->google_token = $googleUser->token;
            $user->google_refresh_token = $googleUser->refreshToken;
            $user->role = User::ROLE_USER;
            $user->email_verified_at = now();

            $user->password = bcrypt(Str::random(16)); // You can generate a random password or leave it empty
            $user->save();
        }
        Auth::login($user, true);
        return response()->json([
            'status' => true,
            'message' => 'Login successful',
            'access_token' => $user->createToken('auth_token')->plainTextToken,
            'data' => $user
        ], 200);
    }
}
