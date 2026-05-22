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
            'kode_gudang' => ['required', 'string', 'max:50', Rule::unique('warehouses', 'kode_gudang')->ignore($warehouseId)],
            'nama_gudang' => ['required', 'string', 'max:100'],
            'tipe' => ['required', 'string', Rule::in(['Kantor Pusat', 'Kantor Cabang'])],
            'parent_id' => ['nullable', 'exists:warehouses,id'],
            'alamat' => ['nullable', 'string'],
            'status' => ['nullable', 'string', Rule::in(['Aktif', 'Tidak Aktif'])],
        ];
    }
}
