<?php

namespace App\Http\Controllers\Api;

use App\Helper\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //register
    public function register(Request $request)
    {
        // Validate the request
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            // Hash the password
            $data['password'] = Hash::make($data['password']);
            // Create the user
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
            ]);

            $user->assignRole('student');

            // generate a token
            $token = $user->createToken('auth_token')->plainTextToken;

            return ApiResponse::success(['token' => $token, 'user' => $user], 'User registered successfully', 201);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 500);
        }
    }

    //login
    public function login(Request $request)
    {
        // validate the request
        $data = $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
        ]);
        try {
            // check if the user exists
            $user = User::where('email', $data['email'])->first();
            if (!$user) {
                return ApiResponse::error('Invalid credentials', [], 401);
            }
            // check if the password is correct
            if (!Hash::check($data['password'], $user->password)) {
                return ApiResponse::error('Invalid credentials', [], 401);
            }

            // generate a token
            $token = $user->createToken('auth_token')->plainTextToken;
            return ApiResponse::success(['token' => $token, 'user' => $user], 'User logged in successfully', 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 500);
        }
    }

    // logout
    public function logout(Request $request)
    {
        // revoke the token
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'User logged out successfully'], 200);
    }

    // get user
    public function user(Request $request)
    {
        // return the user

        try {
            return ApiResponse::success($request->user(), 'User retrieved successfully', 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 500);
        }
    }
    // get accss token
    public function getAccessToken(Request $request)
    {
        // return the access token
        try {
            return ApiResponse::success($request->user()->createToken('auth_token')->plainTextToken, 'Access token retrieved successfully', 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 500);
        }
    }
}
