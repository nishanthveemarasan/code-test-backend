<?php

namespace App\Http\Controllers\API;

use App\Events\NotifyOwnerEvent;
use App\Events\NotifyUserEvent;
use App\Helper\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateContactUsRequest;
use App\Models\ContactUs;
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
            $contact = $this->contactUsService->create($request->validated());
            if($contact){
                NotifyOwnerEvent::dispatch($contact);
                NotifyUserEvent::dispatch($contact);
            }
            return ApiResponse::success("Your Message sent successfully! We will get back to you soon.");
        }catch(Exception $e){
            Log::channel('exception')->error("Error storing contact us message: " . $e->getMessage(). " in file: " . $e->getFile() . " on line: " . $e->getLine());
            return ApiResponse::error(self::ERROR_MSG);
        }
    }
}
