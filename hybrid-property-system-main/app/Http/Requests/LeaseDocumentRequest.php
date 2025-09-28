<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LeaseDocumentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Landlord'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'document' => 'required|file|mimes:pdf,doc,docx|max:10240', // 10MB max
            'document_type' => 'required|string|in:lease_agreement,renewal,termination,amendment,other',
            'description' => 'nullable|string|max:500',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'document.required' => 'Document is required.',
            'document.file' => 'Document must be a valid file.',
            'document.mimes' => 'Document must be a PDF, DOC, or DOCX file.',
            'document.max' => 'Document size cannot exceed 10MB.',
            'document_type.required' => 'Document type is required.',
            'document_type.in' => 'Document type must be one of: lease_agreement, renewal, termination, amendment, other.',
            'description.max' => 'Description cannot exceed 500 characters.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'document' => 'document',
            'document_type' => 'document type',
            'description' => 'description',
        ];
    }
}
