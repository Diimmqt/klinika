<?php

namespace App\Http\Requests;

use App\Models\Practitioner;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePractitionerRequest extends FormRequest
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
        $practitioner = $this->route('practitioner');

        return [
            'name' => ['required', 'string', 'max:150'],
            'practitioner_type' => ['required', 'string', Rule::in(array_keys(Practitioner::PROFESSIONS))],
            'poli_ids' => ['nullable', 'array'],
            'poli_ids.*' => ['integer', Rule::exists('polis', 'id')],
            'user_id' => ['nullable', 'integer', Rule::exists('users', 'id'), Rule::unique('practitioners', 'user_id')->ignore($practitioner)],
            'str_number' => ['nullable', 'string', 'max:100'],
            'sip_number' => ['nullable', 'string', 'max:100'],
            'satusehat_practitioner_id' => ['nullable', 'string', 'max:100'],
            'is_active' => ['required', 'boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'nama tenaga kesehatan',
            'practitioner_type' => 'profesi',
            'poli_ids' => 'poli penugasan',
            'user_id' => 'akun pengguna',
            'str_number' => 'nomor STR',
            'sip_number' => 'nomor SIP',
            'satusehat_practitioner_id' => 'ID SATUSEHAT',
            'is_active' => 'status aktif',
        ];
    }

    protected function prepareForValidation(): void
    {
        $poliIds = array_values(array_filter(
            (array) $this->input('poli_ids', []),
            static fn ($id) => $id !== null && $id !== ''
        ));

        $this->merge([
            'name' => trim((string) $this->input('name')),
            'practitioner_type' => strtolower(trim((string) $this->input('practitioner_type'))),
            'poli_ids' => $poliIds,
            'user_id' => $this->filled('user_id') ? $this->input('user_id') : null,
            'str_number' => $this->filled('str_number') ? trim((string) $this->input('str_number')) : null,
            'sip_number' => $this->filled('sip_number') ? trim((string) $this->input('sip_number')) : null,
            'satusehat_practitioner_id' => $this->filled('satusehat_practitioner_id')
                ? trim((string) $this->input('satusehat_practitioner_id'))
                : null,
            'is_active' => $this->boolean('is_active', true),
        ]);
    }
}

