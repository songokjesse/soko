<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request){
        $fields = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $fields['email'])->first();

        if(!$user || Hash::check($fields['password'], $user->password)){
            return response([
                'message' => 'Access Denied: Bad Credentials'
            ], 401) ;
        }

        $token = $user->createToken('secretToken')->plainTextToken;
        $response = [
            'user' => $user,
            'token' => $token
        ];
        return response($response,201);
    }
    public function register(Request $request){
        $fields = $request->validate([
            'email' => 'required|email|unique:users,email',
            'name' => 'required|string',
            'password' => 'required|string|confirmed'
        ]);

        $user = new User();
        $user->name = $fields['name'];
        $user->email = $fields['email'];
        $user->password = bcrypt($fields['password']);
        $user->save();

        $token = $user->createToken('secretToken')->plainTextToken;
        $response = [
            'user' => $user,
            'token' => $token
        ];
        return response($response,201);
    }


    public function logout(Request $request): array
    {
        auth()->user()->tokens()->delete();
        return [
            'message' => 'User Logged Out'
        ];
    }
}
