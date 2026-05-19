<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreWarehouseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('warehouse.manage');
    }

    public function rules(): array
    {
        return [
            'kode_gudang' => ['required', 'string', 'max:50', 'unique:warehouses,kode_gudang'],
            'nama_gudang' => ['required', 'string', 'max:100'],
            'alamat' => ['nullable', 'string'],
            'kota' => ['nullable', 'string', 'max:100'],
            'provinsi' => ['nullable', 'string', 'max:100'],
            'telepon' => ['nullable', 'string', 'max:20'],
            'penanggung_jawab' => ['nullable', 'string', 'max:100'],
            'keterangan' => ['nullable', 'string'],
            'status' => ['nullable', 'string', Rule::in(['Aktif', 'Tidak Aktif'])],
        ];
    }
}
