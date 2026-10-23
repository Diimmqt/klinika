<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePermissionRequest extends FormRequest
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
        $permissionId = $this->route('permission')?->id ?? $this->route('permission');

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-z0-9_.]+$/',
                Rule::unique('permissions', 'name')
                    ->where(fn ($q) => $q->where('guard_name', 'web'))
                    ->ignore($permissionId),
            ],
            'description' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'nama permission',
            'description' => 'deskripsi',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.regex' => 'Nama permission hanya boleh huruf kecil, angka, titik, dan underscore.',
            'name.unique' => 'Nama permission sudah digunakan.',
        ];
    }
}
