<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePoliRequest extends FormRequest
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
        $poli = $this->route('poli');

        return [
            'code' => ['required', 'string', 'max:30', Rule::unique('polis', 'code')->ignore($poli)],
            'name' => ['required', 'string', 'max:120'],
            'queue_prefix' => ['required', 'string', 'max:5', 'alpha_num', Rule::unique('polis', 'queue_prefix')->ignore($poli)],
            'is_active' => ['required', 'boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'code' => 'kode poli',
            'name' => 'nama poli',
            'queue_prefix' => 'kode antrean',
            'is_active' => 'status',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'code' => strtoupper(trim((string) $this->input('code'))),
            'name' => trim((string) $this->input('name')),
            'queue_prefix' => strtoupper(trim((string) $this->input('queue_prefix'))),
            'is_active' => $this->boolean('is_active', true),
        ]);
    }
}

