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
            'item_code' => ['required', 'string', 'max:100', 'unique:item_descriptions,item_code'],
            'item_description' => ['nullable', 'string'],
            'capacity' => ['required', 'numeric', 'min:0'],
            'uom' => ['required', 'string', 'max:20'],
            'category_id' => ['nullable', 'exists:item_categories,category_id'],
        ];
    }

    public function messages(): array
    {
        return [
            'item_code.required' => 'Kode item wajib diisi.',
            'item_code.unique' => 'Kode item sudah digunakan.',
            'capacity.required' => 'Kapasitas wajib diisi.',
            'capacity.numeric' => 'Kapasitas harus berupa angka.',
            'uom.required' => 'UOM wajib diisi.',
        ];
    }
}
