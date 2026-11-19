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
            'username' => $data['username'] ,
        ]);

        $tokenResult = $user->createToken('API Token');

        return [
            'status' => true,
            'message' => 'User registered successfully',
            'access_token' => $tokenResult->accessToken,
        ];
    }


}
