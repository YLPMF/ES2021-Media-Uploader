<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request) {

        if(Auth::attempt(['name' => $request->username, 'password' => $request->password])) {
            $user = Auth::user();
            return ['token' => $user->createToken('playlist')->accessToken];
        }

        return response()->json('Login failed', 401);
    }

    public function me(Request $request) {
        return response()->json($request->user());
    }
}
