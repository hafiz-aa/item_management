<?php

namespace App\Imports;

use App\Models\Item;
use App\Models\Warehouse;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Illuminate\Support\Facades\Auth;

class ItemsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use Importable, SkipsFailures;

    private array $result = ['success' => 0, 'failed' => 0, 'errors' => []];
    private array $warehouseCache = [];

    public function model(array $row)
    {
        $warehouseId = null;
        if (!empty($row['lokasi_gudang'])) {
            if (!isset($this->warehouseCache[$row['lokasi_gudang']])) {
                $warehouse = Warehouse::where('kode_gudang', $row['lokasi_gudang'])
                    ->orWhere('nama_gudang', $row['lokasi_gudang'])
                    ->first();
                $this->warehouseCache[$row['lokasi_gudang']] = $warehouse?->id;
            }
            $warehouseId = $this->warehouseCache[$row['lokasi_gudang']];
        }

        $this->result['success']++;

        return new Item([
            'kode_tabung' => $row['kode_tabung'],
            'deskripsi_isi_tabung' => $row['deskripsi_isi'] ?? null,
            'serial_no' => $row['serial_no'] ?? null,
            'tahun_pembuatan' => $row['tahun_pembuatan'] ?? null,
            'berat' => $row['berat'] ?? 0,
            'kapasitas' => $row['kapasitas'] ?? 0,
            'uom' => $row['uom'] ?? 'Kg',
            'qty' => $row['qty'] ?? 1,
            'tanggal_perolehan' => $row['tanggal_perolehan'] ?? null,
            'kategori' => $row['kategori'] ?? null,
            'status' => $row['status'] ?? 'Aktif',
            'rusak' => ($row['rusak'] ?? 'Tidak') === 'Ya',
            'dijual' => ($row['dijual'] ?? 'Tidak') === 'Ya',
            'lokasi_gudang_id' => $warehouseId,
            'vendor' => $row['vendor'] ?? null,
            'pemilik_tabung' => $row['pemilik_tabung'] ?? null,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);
    }

    public function rules(): array
    {
        return [
            'kode_tabung' => 'required|string|max:100|unique:items,kode_tabung',
            'serial_no' => 'nullable|string|max:100',
            'tahun_pembuatan' => 'nullable|integer|min:1900|max:' . date('Y'),
            'berat' => 'nullable|numeric|min:0',
            'kapasitas' => 'nullable|numeric|min:0',
            'qty' => 'nullable|integer|min:1',
            'tanggal_perolehan' => 'nullable|date',
            'status' => 'nullable|in:Aktif,Tidak Aktif',
        ];
    }

    public function onFailure(...$failures)
    {
        foreach ($failures as $failure) {
            $this->result['failed']++;
            $this->result['errors'][] = [
                'row' => $failure->row(),
                'attribute' => $failure->attribute(),
                'errors' => $failure->errors(),
            ];
        }
    }

    public function getResult(): array
    {
        return $this->result;
    }
}
