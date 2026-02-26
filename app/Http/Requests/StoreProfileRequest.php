<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProfileRequest extends FormRequest
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
            'uuid'         => 'nullable|uuid',
            'first_name'   => 'required|string|max:255',
            'last_name'    => 'required|string|max:255',
            'biography'     => 'nullable|string',
            'bottom_line'  => 'nullable|string',
            'email'        => [
                'required',
                'email',
                'max:255',
                // Ensure email is unique but allow current user to keep it
                Rule::unique('profiles')->ignore(auth()->id(), 'user_id'),
            ],
            'phone' => 'nullable|string|max:20',
            'address'      => 'nullable|string|max:255',
            'image'        => 'required_if:uuid,null|image|mimes:jpeg,jpg,png|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'First name is required.',
            'last_name.required'  => 'Last name is required.',
            'email.required'      => 'Email is required.',
            'email.email'         => 'Email must be a valid email address.',
            'email.unique'        => 'This email is already taken.',
            'image.image'         => 'The uploaded file must be an image.',
            'image.mimes'         => 'The image must be a file of type: jpeg, jpg, png.',
            'image.max'           => 'The image may not be greater than 2MB.',
        ];
    }
}
