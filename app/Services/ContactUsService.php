<?php

namespace App\Services;
use App\Models\ContactUs;

class ContactUsService{
    public function create(array $data){
        ContactUs::create($data);
        return true;
    }
}