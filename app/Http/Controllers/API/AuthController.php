<?php

namespace App\Http\Controllers\API;

use App\Helper\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRegisterRequest;
use App\Http\Requests\AutLoginRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{

    public function __construct(protected AuthService $authService)
    {
        
    }
    public function register(AuthRegisterRequest $request){

        try{
            $response = $this->authService->register($request->validated());
            if($response){
                return ApiResponse::success(self::SUCCESS_MSG, $response);
            }
        }catch(\Exception $e){
            Log::error('Registration failed: ' . $e->getMessage(). ' in file: ' . $e->getFile() . ' on line: ' . $e->getLine());
            return ApiResponse::error(self::ERROR_MSG);
        }
    }

    public function login(AutLoginRequest $request)
    {
        try{
            $response = $this->authService->login($request->validated());
            if(!$response){
                return ApiResponse::error("Invalid Credentials");
            }
            return ApiResponse::success("Login Successful", $response);
        }catch(\Exception $e){
            Log::error('Registration failed: ' . $e->getMessage(). ' in file: ' . $e->getFile() . ' on line: ' . $e->getLine());
            return ApiResponse::error(self::ERROR_MSG);
        }
    }
}
