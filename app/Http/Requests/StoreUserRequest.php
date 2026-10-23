<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'roles' => ['required', 'array', 'min:1'],
            'roles.*' => ['string', 'exists:roles,name'],
            'send_invitation' => ['nullable', 'boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'nama',
            'email' => 'email',
            'roles' => 'role',
            'send_invitation' => 'kirim undangan',
        ];
    }

    protected function prepareForValidation(): void
    {
        $roles = $this->input('roles');

        if (is_string($roles) && $roles !== '') {
            $this->merge(['roles' => [$roles]]);
        }

        // Filter empty option from select ("-- Pilih role --")
        if (is_array($this->input('roles'))) {
            $this->merge([
                'roles' => array_values(array_filter($this->input('roles'))),
            ]);
        }

        $this->merge([
            'send_invitation' => $this->boolean('send_invitation'),
        ]);
    }
}
