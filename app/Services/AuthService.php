<?php
namespace App\Services;

use App\Helper\ApiResponse;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService{

    public function register(array $data){
        $user =  User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $token = $user->createToken('Personal Access Token')->accessToken;

        return [
            'user' => $user,
            'token' => $token
        ];
    }

    public function login(array $data)
    {
        if (!Auth::attempt($data)) {
            return false;
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $token = $user->createToken('Personal Access Token')->accessToken;

        return [
            'user' => $user->only(['name']),
            'token' => $token
        ];

    }
}
