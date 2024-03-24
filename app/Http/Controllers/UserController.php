<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function deleteUser()
{
    if (!Auth::check()) {
        return response()->json(['error' => 'User not authenticated'], 401);
    }

    $user = Auth::user();

    try {
        $user->delete();
    } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to delete user'], 500);
    }

    return response()->json([
        'status' => 'success',
        'message' => 'User deleted successfully',
    ], 200);}
    public function updateUser(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }
        
        $user = Auth::user();
    
        $validatedData = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'password' => 'nullable|min:8',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
        ]);
    
        $user->first_name = $validatedData['first_name'];
        $user->last_name = $validatedData['last_name'];
        $user->email = $validatedData['email'];
        $user->date_of_birth = $validatedData['date_of_birth'];
        $user->gender = $validatedData['gender'];
    
        if (isset($validatedData['password'])) {
            $user->password = bcrypt($validatedData['password']);
        }
    
        try {
            $user->save();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update user'], 500);
        }
    
        return response()->json([
            'status' => 'success',
            'message' => 'User updated successfully',
            'user' => $user,
        ], 200);
    }
}
