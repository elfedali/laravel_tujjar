<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;

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
                'data' => $user
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

            event(new Registered($user)); // send email verification to user

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
}
