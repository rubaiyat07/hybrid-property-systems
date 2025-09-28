<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $rules = [
            'name' => ['string', 'max:255'],
            'email' => ['email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
            'profile_photo' => ['nullable', 'image', 'max:2048'],
        ];

        if ($this->user()->hasRole('Tenant')) {
            $rules['phone'] = ['nullable', 'string', 'max:20'];
            $rules['documents'] = ['nullable', 'array'];
            $rules['documents.*'] = ['file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'];
        } else {
            $rules['bio'] = ['nullable', 'string', 'max:1000'];
            $rules['phone_verified'] = ['boolean'];
            $rules['documents_verified'] = ['boolean'];
            $rules['screening_verified'] = ['boolean'];
        }

        return $rules;
    }
}
