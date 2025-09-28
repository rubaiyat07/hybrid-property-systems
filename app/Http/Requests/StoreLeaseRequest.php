<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\TenantLead;
use App\Models\User;
use App\Models\Tenant;
use Illuminate\Support\Facades\Hash;

class StoreLeaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Landlord'));
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        $tenantId = $this->input('tenant_id');

        if (str_starts_with($tenantId, 'lead_')) {
            $leadId = substr($tenantId, 5);
            $lead = TenantLead::findOrFail($leadId);

            // Check authorization for lead
            if ($lead->property->owner_id !== auth()->id()) {
                abort(403, 'Unauthorized lead access.');
            }

            // Create user if not exists
            $user = User::firstOrCreate(
                ['email' => $lead->email],
                [
                    'name' => $lead->name,
                    'phone' => $lead->phone ?? '',
                    'password' => Hash::make('password123'), // Temporary password
                ]
            );

            // Assign tenant role if not already
            if (!$user->hasRole('Tenant')) {
                $user->assignRole('Tenant');
            }

            // Create tenant if not exists
            $tenant = Tenant::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'first_name' => $lead->name,
                    'last_name' => '',
                    'phone' => $lead->phone ?? '',
                    'email' => $lead->email,
                ]
            );

            // Set the actual tenant_id and lead_id
            $this->merge([
                'tenant_id' => $tenant->id,
                'lead_id' => $leadId,
            ]);

            // Update lead status to converted
            $lead->update(['status' => 'converted']);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'tenant_id' => 'required|exists:tenants,id',
            'lead_id' => 'nullable|exists:tenant_leads,id',
            'unit_id' => 'required|exists:units,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'rent_amount' => 'required|numeric|min:0|max:999999.99',
            'deposit' => 'nullable|numeric|min:0|max:999999.99',
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
            'tenant_id.required' => 'Please select a tenant.',
            'tenant_id.exists' => 'The selected tenant is invalid.',
            'lead_id.exists' => 'The selected lead is invalid.',
            'unit_id.required' => 'Please select a unit.',
            'unit_id.exists' => 'The selected unit is invalid.',
            'start_date.required' => 'Start date is required.',
            'start_date.after_or_equal' => 'Start date must be today or later.',
            'end_date.required' => 'End date is required.',
            'end_date.after' => 'End date must be after start date.',
            'rent_amount.required' => 'Rent amount is required.',
            'rent_amount.numeric' => 'Rent amount must be a valid number.',
            'rent_amount.min' => 'Rent amount must be at least 0.',
            'rent_amount.max' => 'Rent amount cannot exceed 999,999.99.',
            'deposit.numeric' => 'Deposit must be a valid number.',
            'deposit.min' => 'Deposit must be at least 0.',
            'deposit.max' => 'Deposit cannot exceed 999,999.99.',
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
            'tenant_id' => 'tenant',
            'lead_id' => 'lead',
            'unit_id' => 'unit',
            'start_date' => 'start date',
            'end_date' => 'end date',
            'rent_amount' => 'rent amount',
            'deposit' => 'deposit',
            'document' => 'lease document',
            'notes' => 'notes',
        ];
    }
}
