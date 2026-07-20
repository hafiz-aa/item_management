<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $uomId = $this->route('uom');

        return [
            'uom_code' => ['required', 'string', 'max:50', Rule::unique('uom', 'uom_code')->ignore($uomId, 'uom_id')],
            'uom_name' => ['required', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'uom_code.required' => 'Kode unit wajib diisi.',
            'uom_code.unique' => 'Kode unit sudah digunakan.',
            'uom_name.required' => 'Nama unit wajib diisi.',
        ];
    }
}
