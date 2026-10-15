<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVisitRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'patient_id' => [
                'required',
                'exists:patients,id',
            ],
            'poli_id' => [
                'required',
                'exists:polis,id',
            ],
            'visit_date' => [
                'required',
                'date',
            ],
            'complaint' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'patient_id' => 'Pasien',
            'poli_id' => 'Poli Tujuan',
            'visit_date' => 'Tanggal Kunjungan',
            'complaint' => 'Keluhan Pasien',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'patient_id.required' => 'Pilih pasien yang terdaftar terlebih dahulu.',
            'patient_id.exists' => 'Data pasien yang dipilih tidak ditemukan di sistem.',
            'poli_id.required' => 'Poli tujuan pelayanan wajib dipilih.',
            'poli_id.exists' => 'Poli tujuan tidak valid.',
            'visit_date.required' => 'Tanggal kunjungan wajib diisi.',
            'visit_date.date' => 'Format tanggal kunjungan tidak valid.',
        ];
    }
}
