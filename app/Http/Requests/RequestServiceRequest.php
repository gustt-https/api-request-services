<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RequestServiceRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'description' => ['required', 'string'],
            'latitude' => ['required', 'string'],
            'longitude' => ['required', 'string'],
            'address' => ['required', 'string'],
            'address_number' => ['required', 'string'],
            'complement' => ['required', 'string'],
            'price' => ['required', 'string']
        ];
    }
}
