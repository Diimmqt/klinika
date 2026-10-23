<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRoleRequest extends FormRequest
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
            'name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-Z0-9_\-\s]+$/',
                Rule::unique('roles', 'name')->where(fn ($q) => $q->where('guard_name', 'web')),
                Rule::notIn([
                    'super_admin', 'admin_klinik', 'pendaftaran', 'perawat',
                    'dokter', 'apoteker', 'kasir', 'manajemen',
                    'Super Admin', 'Admin Klinik', 'Pendaftaran', 'Perawat',
                    'Dokter', 'Apoteker', 'Kasir', 'Manajemen',
                ]),
            ],
            'description' => ['required', 'string', 'max:500'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'nama role',
            'description' => 'deskripsi',
            'permissions' => 'permission',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.regex' => 'Nama role hanya boleh huruf kecil, angka, dan underscore.',
            'name.unique' => 'Nama role sudah digunakan.',
        ];
    }
}
