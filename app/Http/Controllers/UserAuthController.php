<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserAuthController extends Controller
{
    public function register(Request $request)
    {
        $validate_data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|min:8'
        ]);

        $user = User::create([
            'name' => $validate_data['name'],
            'email' => $validate_data['email'],
            'password' => Hash::make($validate_data['password']),
        ]);

        return response()->json(['message' => 'User Created']);
    }

    public function login(Request $request)
    {
        $validate_data = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|min:8'
        ]);

        $user = User::where('email', $validate_data['email'])->first();

        if (!$user || !Hash::check($validate_data['password'], $user->password)) {
            return response()->json(['message' => 'Invalid Credentials'], 401);
        }

        if ($user->tokens->count() > 0) {
            $user->tokens()->delete();
        }

        $token = $user->createToken($user->name . '-AuthToken')->plainTextToken;

        return response()->json(['access_token' => $token]);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();
        return response()->json(["message" => "logged out"]);
    }
}
