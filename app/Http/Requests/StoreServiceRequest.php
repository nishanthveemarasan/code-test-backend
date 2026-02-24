<?php

namespace App\Http\Requests;

use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreServiceRequest extends FormRequest
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
        $serviceId = $this->route('service')?->id;
        return [
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('services')
                    ->where(
                        fn(Builder $query) =>
                        $query->where('user_id', auth()->id())
                            ->whereNull('deleted_at') // Ignore deleted records in validation
                    )
                    ->ignore($serviceId)
            ],
            'description' => ['required', 'string'],
            'points' => ['required', 'array'],
            'points.*' => ['required', 'string'],
            'special_point' => ['nullable', 'string'],
        ];
    }
}
