<?php

namespace App\Services;
use App\Models\ContactUs;

class ContactUsService{
    public function create(array $data){
        return ContactUs::create($data);
    }
}