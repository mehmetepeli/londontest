<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function update_profile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email',
            'mobile_number' => 'required|min:11|max:11'
        ]);

        if($validator->fails()) {
            return response()->json([
                'message' => 'Validations fail',
                'errors' => $validator->errors()
            ],422);
        }

        $user = $request->user();

        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'mobile_number' => $request->mobile_number
        ]);

        return response()->json([
            'message' => 'Profile successfully updated'
        ],200);
    }

    public function getAllUsers()
    {
        return User::all();
    }

    public function getUserWithFilter(Request $request, User $user)
    {
        if ($request->has('search')) {
            return $user->where('first_name', $request->input('search'))
                        ->orWhere('last_name', $request->input('search'))
                        ->orWhere('email', $request->input('search'))
                        ->orWhere('mobile_number', $request->input('search'))
                        ->get();
        }

        return User::all();
    }
}
