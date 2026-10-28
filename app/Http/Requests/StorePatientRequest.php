<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePatientRequest extends FormRequest
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
            'nik' => [
                'nullable',
                'digits:16',
            ],
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'gender' => [
                'required',
                'in:L,P',
            ],
            'birth_place' => [
                'required',
                'string',
                'max:255',
            ],
            'birth_date' => [
                'required',
                'date',
                'before_or_equal:today',
            ],
            'phone' => [
                'nullable',
                'string',
                'min:10',
                'max:15',
                'regex:/^[0-9]+$/',
            ],
            'address' => [
                'nullable',
                'string',
            ],
            'blood_type' => [
                'nullable',
                'in:A,B,AB,O,-',
            ],
            'satusehat_ihs_number' => [
                'nullable',
                'string',
                'max:255',
            ],
            'occupation' => ['nullable', 'string', 'max:255'],
            'emergency_contact_name' => ['nullable', 'string', 'max:255'],
            'emergency_contact_relationship' => ['nullable', 'string', 'max:100'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:20'],
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
            'nik' => 'NIK',
            'name' => 'Nama lengkap',
            'gender' => 'Jenis kelamin',
            'birth_place' => 'Tempat lahir',
            'birth_date' => 'Tanggal lahir',
            'phone' => 'Nomor telepon',            'address' => 'Alamat domisili',
            'blood_type' => 'Golongan darah',
            'satusehat_ihs_number' => 'Nomor IHS SATUSEHAT',
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
            'nik.digits' => 'NIK harus berjumlah tepat 16 digit angka.',
            'nik.unique' => 'NIK ini sudah terdaftar pada sistem pasien.',
            'name.required' => 'Nama lengkap pasien wajib diisi.',
            'gender.required' => 'Pilih jenis kelamin pasien.',
            'birth_place.required' => 'Tempat lahir wajib diisi.',
            'birth_date.required' => 'Tanggal lahir wajib diisi.',
            'birth_date.before_or_equal' => 'Tanggal lahir tidak boleh melebihi hari ini.',
            'phone.min'   => 'Nomor telepon harus 10–15 digit.',
            'phone.max'   => 'Nomor telepon harus 10–15 digit.',
            'phone.regex' => 'Nomor telepon harus berupa angka.',
        ];
    }
}
