<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
        ]);

        $user = User::create([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'date_of_birth' => $request->input('date_of_birth'),
            'gender' => $request->input('gender'),
        ]);

        $token = Auth::login($user);

        return response()->json([
            'status' => 'success',
            'message' => 'User Registered Successfully',
            'user' => $user,
            'token' => $token
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Login Failed',
            ]);
        }

        $credentials= $request->only('email','password');
        $token=Auth::attempt($credentials);
        $user = Auth::user();

        return response()->json([
            'status' => 'success',
            'message' => 'Login Successfully',
            'token' => $token,
            'user' => $user
        ]);
    }

    public function profile(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'status' => 'success',
            'message' => 'Profile information retrieved successfully',
            'user' => $user,
        ]);
    }
}
