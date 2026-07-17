<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateWarehouseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('warehouse.manage');
    }

    public function rules(): array
    {
        $warehouseId = $this->route('warehouse');

        return [
            'whsl_code' => ['required', 'string', 'max:50', Rule::unique('warehouse_location', 'whsl_code')->ignore($warehouseId, 'whsl_id')],
            'whsl_name' => ['required', 'string', 'max:255'],
            'whsl_type' => ['required', 'string', 'in:0,1'],
            'branch_id' => ['required', 'integer', 'exists:branch,branch_id'],
            'whsl_parent_id' => ['nullable', 'integer', 'exists:warehouse_location,whsl_id'],
            'whsl_status' => ['nullable', 'string', 'in:0,1'],
        ];
    }
}
