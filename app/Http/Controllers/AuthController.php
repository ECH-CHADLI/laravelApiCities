<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request) {
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed'
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password'])
        ]);

        $token = $user->createToken('usertoken')->plainTextToken; 

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    public function logout(Request $request) {
        auth()->user()->tokens()->delete(); // auth() is a global helper that provides access to the authentication service, it returns an instance of the authentication factory
        return [
            'message' => 'Logged out'
        ];
    }

    public function login(Request $request) {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        // checking email
        $user = User::where('email', $fields['email'])->first();  // the scope resolution operator(::) is used for calling static methods and properties (class level, and namespace resolution), the arrow operator(->) for normal methods and properties for an object

        // checking password
        if(!$user || !Hash::check($fields['password'], $user->password)) { 
            return response([
                'message' => 'Wrong creds'
            ], 401);
        }

        $token = $user->createToken('usertoken')->plainTextToken; 

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    public function getUser(Request $request) {
        try {
            return response()->json(['user' => $request->user()]); // retrieve authenticated user
        } catch (\Exception $e) {
            // Log the exception
            \Log::error($e);

            // Return a generic error response
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }
}
