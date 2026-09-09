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
            // Gallery picks are not re-encoded by the app, so the bytes may be
            // whatever the phone saved. `mimes` matches sniffed content, not the
            // filename the client sends.
            'document_front' => ['required', 'image', 'mimes:jpeg,jpg,png,webp'],
            'document_verse' => ['required', 'image', 'mimes:jpeg,jpg,png,webp'],
            'selfie' => ['required', 'image', 'mimes:jpeg,jpg,png,webp'],
        ];
    }
}
