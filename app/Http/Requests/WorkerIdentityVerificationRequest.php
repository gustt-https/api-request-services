<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class WorkerIdentityVerificationRequest extends FormRequest
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
            'document_type' => ['required', 'in:cnh,rg'],
            'document_number' => ['required', 'string'],
            'document_front' => ['required', 'image', 'mimes:jpeg,jpg'],
            'document_verse' => ['required', 'image', 'mimes:jpeg,jpg'],
            'selfie' => ['required', 'image', 'mimes:jpeg,jpg'],
        ];
    }
}
