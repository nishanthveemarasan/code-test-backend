<?php

namespace App\Http\Controllers\API;

use App\Helper\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\SaveContactInfoRequest;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function __construct(protected UserService $userService)
    {
        
    }
    
    /**
     * Method store
     *
     * @param SaveContactInfoRequest $request [explicite description]
     *
     * @return void
     */
    public function store(SaveContactInfoRequest $request){

        try{
            $response = $this->userService->store($request->validated(), auth()->user());
            if($response){
                return ApiResponse::success("Contact info saved successfully");
            }
        }catch(\Exception $e){
            Log::channel('exception')->error('Registration failed: ' . $e->getMessage(). ' in file: ' . $e->getFile() . ' on line: ' . $e->getLine());
            return ApiResponse::error("Failed to save contact info");
        }
    }

    


}
