<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Str;

class AuthController extends Controller
{

    /**
     * Log in the user using email or phone number.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                'login' => 'required',
                'password' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->first(),
                ], 400);
            }

            $loginField = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone_number';

            if (!auth()->attempt([$loginField => $request->login, 'password' => $request->password])) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid credentials',
                ], 400);
            }

            $user = User::where($loginField, $request->login)->first();

            return response()->json([
                'status' => true,
                'message' => 'Login successful',
                'access_token' => $user->createToken('auth_token')->plainTextToken,
                'user' => $user
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Register a new user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|unique:users,email',
                'password' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->first(),
                ], 400);
            }

            $user = User::create(array_merge(
                $validator->validated(),
                ['password' => Hash::make($request->password)]
            ));

            // event(new Registered($user)); // send email verification to user

            $user->refresh(); // refresh user data

            return response()->json([
                'status' => true,
                'message' => 'User created successfully',
                'token' => $user->createToken('auth_token')->plainTextToken,
                'user' => $user
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * login with google
     */
    public function loginWithGoogle(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id_token' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->first(),
                ], 400);
            }

            $client = new \Google_Client(['client_id' => config('services.google.client_id')]);  // Specify the CLIENT_ID of the app that accesses the backend
            $payload = $client->verifyIdToken($request->id_token);

            if ($payload) {
                $user = User::where('email', $payload['email'])->first();
                if (!$user) {
                    $user = User::create([
                        'email' => $payload['email'],
                        'role' => \App\Models\User::ROLE_USER,

                        'first_name' => $payload['given_name'],
                        'last_name' => $payload['family_name'],

                        'photo' => $payload['picture'],

                        'provider' => 'google',
                        'provider_id' => $payload['sub'],

                        'password' => Hash::make(Str::random(8)),
                        'email_verified_at' => now(),

                    ]);
                }
                $user->refresh(); // refresh user data
                return response()->json([
                    'status' => true,
                    'message' => 'Login successful',
                    'access_token' => $user->createToken('auth_token')->plainTextToken,
                    'data' => $user
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid credentials',
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
