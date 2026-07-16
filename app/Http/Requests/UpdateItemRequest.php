<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('item.edit');
    }

    public function rules(): array
    {
        $itemId = $this->route('item')->itemh_id;

        return [
            'item_code' => ['required', 'string', 'max:100', Rule::unique('item_headers', 'item_code')->ignore($itemId, 'itemh_id')],
            'item_name' => ['nullable', 'string'],
            'capacity' => ['nullable', 'numeric', 'min:0'],
            'uom_id_1' => ['nullable', 'string', 'max:20'],
            'uom_id_2' => ['nullable', 'string', 'max:20'],
            'cat_id' => ['nullable', 'string', 'max:100'],
            'company_id' => ['nullable', 'integer'],
            'branch_id' => ['nullable', 'integer', 'exists:branches,branch_id'],
            'whsl_id' => ['nullable', 'integer'],
            'acquired_date' => ['nullable', 'date'],
            'itemd_code' => ['nullable', 'string', 'max:100'],
            'qty' => ['nullable', 'integer', 'min:1'],
            'status' => ['nullable', 'string', Rule::in(['Aktif', 'Tidak Aktif'])],
            'position_id' => ['nullable', 'integer'],
            'is_broken' => ['nullable', 'boolean'],
            'is_dispossed' => ['nullable', 'boolean'],
            'is_writeoff' => ['nullable', 'boolean'],
            'warehouse_id' => ['nullable', 'integer', 'exists:warehouses,warehouse_id'],
            'original_branch_id' => ['nullable', 'integer', 'exists:branches,branch_id'],
        ];
    }

    public function messages(): array
    {
        return [
            'item_code.required' => 'Kode item wajib diisi.',
            'item_code.unique' => 'Kode item sudah digunakan.',
        ];
    }
}
