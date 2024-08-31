<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
     // User Registration
     public function register(Request $request)
     {
         $request->validate([
             'name' => 'required|string|max:255',
             'email' => 'required|string|email|max:255|unique:users',
             'password' => 'required|string|min:8|confirmed',
         ]);
 
         $user = User::create([
             'name' => $request->name,
             'email' => $request->email,
             'password' => Hash::make($request->password),
         ]);
 
         Auth::login($user);
 
         return response()->json(['message' => 'User registered successfully', 'user' => $user], 201);
     }
 
     // User Login
     public function login(Request $request)
     {
         $request->validate([
             'email' => 'required|string|email',
             'password' => 'required|string',
         ]);
 
         if (Auth::attempt($request->only('email', 'password'))) {
             $user = Auth::user();
             return response()->json(['message' => 'Login successful', 'user' => $user]);
         }
 
         return response()->json(['message' => 'Invalid credentials'], 401);
     }
 
     // Password Reset
     public function resetPassword(Request $request)
     {
         $request->validate([
             'email' => 'required|string|email',
         ]);
 
         $response = Password::sendResetLink($request->only('email'));
 
         if ($response == Password::RESET_LINK_SENT) {
             return response()->json(['message' => 'Password reset link sent']);
         }
 
         return response()->json(['message' => 'Unable to send password reset link'], 500);
     }

     public function generateToken(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->generateToken('YourAppName');

            return response()->json([
                'token' => $token
            ]);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }
}
