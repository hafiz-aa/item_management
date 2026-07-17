<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreItemDescriptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('item-description.create');
    }

    public function rules(): array
    {
        return [
            'masti_code' => ['required', 'string', 'max:50', 'unique:master_item,masti_code'],
            'masti_name' => ['nullable', 'string', 'max:255'],
            'masti_capacity' => ['nullable', 'string', 'max:100'],
            'uom_id_1' => ['required', 'integer', 'exists:uom,uom_id'],
            'cati_id' => ['required', 'integer', 'exists:category_item,cati_id'],
        ];
    }

    public function messages(): array
    {
        return [
            'masti_code.required' => 'Kode item wajib diisi.',
            'masti_code.unique' => 'Kode item sudah digunakan.',
            'uom_id_1.required' => 'UOM wajib diisi.',
            'cati_id.required' => 'Kategori wajib diisi.',
        ];
    }
}
