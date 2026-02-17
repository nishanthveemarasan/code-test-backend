<?php

namespace App\Http\Controllers\API;

use App\Helper\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateContactUsRequest;
use App\Services\ContactUsService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ContactUsController extends Controller
{
    public function __construct(protected ContactUsService $contactUsService)
    {
        
    }
    public function store(CreateContactUsRequest $request){
        try{
            $this->contactUsService->create($request->validated());
            return ApiResponse::success("Your Message sent successfully");
        }catch(Exception $e){
            Log::error("Error storing contact us message: " . $e->getMessage(). " in file: " . $e->getFile() . " on line: " . $e->getLine());
            return ApiResponse::error(self::ERROR_MSG);
        }
    }
}
