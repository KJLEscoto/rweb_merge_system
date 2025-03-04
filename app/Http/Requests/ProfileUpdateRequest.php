<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
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
        return [
            'current_password' => [
                Rule::requiredIf(function () {
                    // Require current_password only if password is provided
                    return $this->filled('password') || $this->filled('password_confirmation');
                }),
                function ($attribute, $value, $fail) {
                    // Verify the current password if it's filled
                    if ($this->filled('current_password') && !Hash::check($value, $this->user()->password)) {
                        $fail('The current password is incorrect.');
                    }
                },
            ],
            'name' => ['sometimes', 'string', 'max:255'],
            'email' => [
                'sometimes',
                'email',
                'max:255',
                Rule::unique('users')->ignore($this->user()->id),
            ],
            'phone' => ['sometimes', 'string', 'max:20'],
            'address' => ['sometimes', 'string'],
            'image' => ['sometimes', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'password' => [
                'nullable', // Allow null if not updating
                'string',
                'min:8',
                'confirmed',
            ],
        ];
    }

    /**
     * Customize validation messages.
     */
    public function messages(): array
    {
        return [
            'current_password.required' => 'The current password is required when setting a new password.',
            'password.confirmed' => 'The password confirmation does not match.',
        ];
    }
}
