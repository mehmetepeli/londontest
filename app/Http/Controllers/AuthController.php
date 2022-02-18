<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $fields = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|string|email|unique:users,email',
            'mobile_number' => 'required|string|unique:users,mobile_number',
            'password' => 'required|string|confirmed'
        ]);

        $user = User::create([
            'first_name' => $fields['first_name'],
            'last_name' => $fields['last_name'],
            'email' => $fields['email'],
            'mobile_number' => $fields['mobile_number'],
            'password' => bcrypt($fields['password'])
        ]);

        $token = $user->createToken('testtoken')->accessToken;

        $response = [
            'user' => $user,
            'token' => $token,
        ];

        return response($response,200);
    }

    public function login(Request $request)
    {
        if(Auth::attempt([
            'email' => $request->email,
            'password' => $request->password
        ])) {
            $user = Auth::user();
            $resArr = [];
            $resArr['token'] = $user->createToken('testtoken')->accessToken;
            $resArr['user'] = $user;

            return response($resArr, 200);
        } else {
            return response()->json(['error' => 'Unauthorized Access'], 203);
        }
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Logged out'
        ];
    }
}
