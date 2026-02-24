<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Database\Query\Builder;

class StoreProjectRequest extends FormRequest
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
        $projectId = $this->route('project')?->id;
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('projects')
                    ->where(
                        fn(Builder $query) =>
                        $query->where('user_id', auth()->id())
                            ->whereNull('deleted_at')
                    )
                    ->ignore($projectId)
            ],
            'type' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'image' =>['required', 'image', 'mimes:jpeg,jpg,png', 'max:2048'],
        ];
    }
}
