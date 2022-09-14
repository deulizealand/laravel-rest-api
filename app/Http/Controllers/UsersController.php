<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Hash;

class UsersController extends Controller
{
    public function store(Request $request)
    {
        try {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            return response()->json([
                'status' => true,
                'message' => "User Registered",
                'data' => $request->all(),
            ]);
        } catch (QueryException $e) {
            return response()->json([
                'status' => false,
                'message' => "User Failed Registered",
                'data' => $e,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => "User Failed Registered",
                'data' => $e,
            ]);
        }
    }
}
