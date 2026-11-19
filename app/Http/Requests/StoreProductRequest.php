<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'discountPrice' => ['required', 'numeric', 'min:0', 'lte:price'],
            'stock' => ['required', 'integer', 'min:0'],
            'category' => ['nullable', 'string'],
            'currency_id' => ['required', 'exists:currencies,id'],
            'personalizable' => ['required', 'boolean'],
            'personalizationFee' => [
                'required_if:personalizable,true',
                'nullable',
                'numeric',
                'min:0'
            ],
            'placements' => [
                'required_if:personalizable,true',
                'array'
            ],
            'placements.*' => ['string', 'in:lid,body,both'],
            'images' => ['required', 'array', 'min:1'],
            'images.*' => ['string'] 
        ];
    }
}
