<?php

namespace App\Http\Requests;

use App\Enums\SkillAction;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class StoreSkillRequest extends FormRequest
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
            'skills' => ['required', 'array'],
            'skills.*.uuid' => ['nullable', 'uuid'],
            'skills.*.name' => ['required', 'string'],
            'skills.*.action' => [
                'required', 
                Rule::enum(SkillAction::class)
            ],
        ];
    }
}
