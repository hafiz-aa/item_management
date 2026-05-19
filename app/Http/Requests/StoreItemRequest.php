<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('item.create');
    }

    public function rules(): array
    {
        return [
            'kode_tabung' => ['required', 'string', 'max:100', 'unique:items,kode_tabung'],
            'deskripsi_isi_tabung' => ['nullable', 'string'],
            'serial_no' => ['nullable', 'string', 'max:100', 'unique:items,serial_no'],
            'tahun_pembuatan' => ['nullable', 'integer', 'min:1900', 'max:' . date('Y')],
            'berat' => ['nullable', 'numeric', 'min:0'],
            'kapasitas' => ['nullable', 'numeric', 'min:0'],
            'uom' => ['nullable', 'string', 'max:20'],
            'qty' => ['nullable', 'integer', 'min:1'],
            'tanggal_perolehan' => ['nullable', 'date'],
            'kategori' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'string', Rule::in(['Aktif', 'Tidak Aktif'])],
            'rusak' => ['nullable', 'boolean'],
            'dijual' => ['nullable', 'boolean'],
            'lokasi_gudang_id' => ['nullable', 'integer', 'exists:warehouses,id'],
            'vendor' => ['nullable', 'string', 'max:100'],
            'pemilik_tabung' => ['nullable', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'kode_tabung.required' => 'Kode tabung wajib diisi.',
            'kode_tabung.unique' => 'Kode tabung sudah digunakan.',
            'serial_no.unique' => 'Serial number sudah digunakan.',
            'lokasi_gudang_id.exists' => 'Gudang tidak ditemukan.',
        ];
    }
}
