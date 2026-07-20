<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'custtp_code' => ['required', 'string', 'max:50', 'unique:customer_type,custtp_code'],
            'custtp_name' => ['required', 'string', 'max:150'],
            'custtp_is_active' => ['required', 'string', 'in:0,1'],
        ];
    }

    public function messages(): array
    {
        return [
            'custtp_code.required' => 'Kode tipe wajib diisi.',
            'custtp_code.unique' => 'Kode tipe sudah digunakan.',
            'custtp_name.required' => 'Nama tipe wajib diisi.',
            'custtp_is_active.required' => 'Status wajib dipilih.',
        ];
    }
}
