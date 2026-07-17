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
        $itemId = $this->route('item')->masti_id;

        return [
            'masti_code' => ['required', 'string', 'max:50', Rule::unique('master_item', 'masti_code')->ignore($itemId, 'masti_id')],
            'masti_name' => ['nullable', 'string', 'max:255'],
            'masti_capacity' => ['nullable', 'string', 'max:100'],
            'uom_id_1' => ['required', 'integer', 'exists:uom,uom_id'],
            'cati_id' => ['nullable', 'integer', 'exists:category_item,cati_id'],
            'comp_id' => ['nullable', 'integer'],
            'branch_id' => ['nullable', 'integer', 'exists:branch,branch_id'],
            'whsl_id' => ['nullable', 'integer', 'exists:warehouse_location,whsl_id'],
            'itemd_acquired_date' => ['nullable', 'date'],
            'itemd_code' => ['nullable', 'string', 'max:50'],
            'itemd_qty' => ['nullable', 'numeric', 'min:1'],
            'itemd_status' => ['nullable', 'string', 'in:0,1'],
            'itemd_position' => ['nullable', 'string', 'max:15'],
            'itemd_is_broken' => ['nullable', 'string', 'in:0,1'],
            'itemd_is_dispossed' => ['nullable', 'string', 'in:0,1'],
            'itemd_is_wo' => ['nullable', 'string', 'in:0,1'],
            'original_branch_id' => ['nullable', 'integer'],
            'uom_id' => ['nullable', 'integer', 'exists:uom,uom_id'],
        ];
    }

    public function messages(): array
    {
        return [
            'masti_code.required' => 'Kode item wajib diisi.',
            'masti_code.unique' => 'Kode item sudah digunakan.',
        ];
    }
}
