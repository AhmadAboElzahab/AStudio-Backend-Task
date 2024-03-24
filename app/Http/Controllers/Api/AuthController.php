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
            'name'=>'required',
            'email'=>'required|email',
            'password'=>'required|min:8',

        ]);
        $user=User::create(([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>$request->password,
        ]));
        $token = Auth::login(($user));
        return response()->json([
            'status'=>'success',
            'message'=>'User Registerd Successfully',
            'user'=>$user,
            'token'=>$token
        ]);
       
    }
    public function login(Request $request)
    {
        $request->validate([
            'email'=>'required|email',
            'password'=>'required',

        ]);
        $credentials= $request->only('email','password');
        $token=Auth::attempt($credentials);
        if(!$token){
            return response()->json([
                'status'=>'error',
                'message'=>'login Faild',
          
            ]);
        }
        return response()->json([
            'status'=>'success',
            'message'=>'login Succesfully',
            'token'=>$token
      
        ]);
    }


}
