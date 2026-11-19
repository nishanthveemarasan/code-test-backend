<?php

namespace App\Http\Controllers\API;

use App\Events\NotifyOwnerEvent;
use App\Events\NotifyUserEvent;
use App\Helper\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateContactUsRequest;
use App\Mail\SendContactSubmissionErrorMail;
use App\Services\ContactUsService;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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
            $this->logException("Contact Us Submission Failed : ", $e, $request->validated(),true);
            return ApiResponse::error(self::ERROR_MSG);
        }
    }
}
