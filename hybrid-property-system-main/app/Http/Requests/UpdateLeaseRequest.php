<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLeaseRequest extends FormRequest
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
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'rent_amount' => 'required|numeric|min:0|max:999999.99',
            'deposit' => 'nullable|numeric|min:0|max:999999.99',
            'status' => 'required|in:active,expired,terminated',
            'document' => 'nullable|file|mimes:pdf,doc,docx|max:10240', // 10MB max
            'notes' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'start_date.required' => 'Start date is required.',
            'start_date.date' => 'Start date must be a valid date.',
            'end_date.required' => 'End date is required.',
            'end_date.after' => 'End date must be after start date.',
            'rent_amount.required' => 'Rent amount is required.',
            'rent_amount.numeric' => 'Rent amount must be a valid number.',
            'rent_amount.min' => 'Rent amount must be at least 0.',
            'rent_amount.max' => 'Rent amount cannot exceed 999,999.99.',
            'deposit.numeric' => 'Deposit must be a valid number.',
            'deposit.min' => 'Deposit must be at least 0.',
            'deposit.max' => 'Deposit cannot exceed 999,999.99.',
            'status.required' => 'Status is required.',
            'status.in' => 'Status must be active, expired, or terminated.',
            'document.file' => 'Document must be a valid file.',
            'document.mimes' => 'Document must be a PDF, DOC, or DOCX file.',
            'document.max' => 'Document size cannot exceed 10MB.',
            'notes.max' => 'Notes cannot exceed 1000 characters.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'start_date' => 'start date',
            'end_date' => 'end date',
            'rent_amount' => 'rent amount',
            'deposit' => 'deposit',
            'status' => 'status',
            'document' => 'lease document',
            'notes' => 'notes',
        ];
    }
}
