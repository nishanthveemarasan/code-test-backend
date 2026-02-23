<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateContactUsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:50',
            'email' => 'required|email|max:100',
            'phone' => 'required|string|regex:/^\+?[1-9]\d{1,14}$/|max:15',
            'subject' => 'required|string|max:100',
            'message' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'A Name is required',
            'email.required' => 'An Email address is required',
            'phone.required' => 'A Valid Phone number is required',
            'phone.regex' => 'A Valid Phone number is required',
            'subject.required' => 'A Subject is required',
            'message.required' => 'A Message is required',
        ];
    }

}
