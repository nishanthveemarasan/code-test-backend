<?php

namespace App\Http\Requests;

use App\Enums\SkillAction;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMainPageRequest extends FormRequest
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
            'title' => ['nullable','string','max:255'],
            'description' => ['nullable','string'],
            'images' => ['nullable','array'],
            'images.*.file' => ['required_if:images.*.action,add','image','mimes:jpeg,jpg,png','max:4096'],
            'images.*.title' => ['nullable','string','max:255'],
            'images.*.action' => ['required', Rule::enum(SkillAction::class)],
            'images.*.order' => ['nullable','integer'],
            'images.*.uuid'   => ['required_if:images.*.action,delete','uuid']
        ];
    }
}
