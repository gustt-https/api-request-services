<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'service_package_id' => [
                'required',
                'integer',
                Rule::exists('service_packages', 'id')->where('is_active', true),
            ],
            'description' => ['nullable', 'string'],
            'latitude' => ['required', 'string'],
            'longitude' => ['required', 'string'],
            'cep' => ['required', 'string'],
            'address' => ['required', 'string'],
            'address_number' => ['required', 'string'],
            'complement' => ['nullable', 'string'],
            'photos' => ['required', 'array', 'min:1', 'max:3'],
            'photos.*' => ['required', 'image', 'mimes:jpeg,jpg,png,webp', 'max:5120'],
        ];
    }
}
