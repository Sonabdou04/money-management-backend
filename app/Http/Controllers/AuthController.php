<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validatedFields = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::create($validatedFields);

        $token = $user->createToken('money-management-react')->plainTextToken;

        return response()->json(['message' => 'User registered successfully!', 'user' => $user, 'token' => $token], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255|exists:users',
            'password' => 'required|min:8',
        ]);

        $user = User::where('email', $request->email)->first();

        if(!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        
        $token = $user->createToken('money-management-react')->plainTextToken;

        return response()->json(['message' => 'Login successful!', 'user' => $user, 'token' => $token]);
    }
}
