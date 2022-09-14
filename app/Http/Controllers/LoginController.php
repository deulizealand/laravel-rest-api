<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        if (!Auth::attempt($request->only(['email', 'password']))) {
            return response()->json([
                'success' => false,
                'message' => "Email or Password doesn't exist",
            ]);
        }

        $token = auth()->user()->createToken($request->email);
        $data = [
            'token' => $token,
        ];

        return response()->json([
            'success' => true,
            'message' => 'Login Success',
            'data' => $data
        ]);
    }
}
