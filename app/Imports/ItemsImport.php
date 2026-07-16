<?php

namespace App\Imports;

use App\Models\ItemDetail;
use App\Models\ItemHeader;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ItemsImport implements SkipsOnFailure, ToModel, WithHeadingRow, WithValidation
{
    use Importable, SkipsFailures;

    private array $result = ['success' => 0, 'failed' => 0, 'errors' => []];

    private array $warehouseCache = [];

    public function model(array $row)
    {
        $warehouseId = null;
        if (! empty($row['lokasi_gudang'])) {
            if (! isset($this->warehouseCache[$row['lokasi_gudang']])) {
                $warehouse = Warehouse::where('kode_gudang', $row['lokasi_gudang'])
                    ->orWhere('nama_gudang', $row['lokasi_gudang'])
                    ->first();
                $this->warehouseCache[$row['lokasi_gudang']] = $warehouse?->warehouse_id;
            }
            $warehouseId = $this->warehouseCache[$row['lokasi_gudang']];
        }

        $header = ItemHeader::create([
            'item_code' => $row['item_code'],
            'item_name' => $row['item_name'] ?? null,
            'capacity' => $row['capacity'] ?? 0,
            'uom_id_1' => $row['uom'] ?? 'Kg',
            'cat_id' => $row['kategori'] ?? null,
            'created_by' => Auth::id(),
        ]);

        ItemDetail::create([
            'itemh_id' => $header->itemh_id,
            'itemd_code' => $row['serial_no'] ?? null,
            'qty' => $row['qty'] ?? 1,
            'status' => $row['status'] ?? 'Aktif',
            'acquired_date' => $row['tanggal_perolehan'] ?? null,
            'warehouse_id' => $warehouseId,
            'created_by' => Auth::id(),
        ]);

        $this->result['success']++;

        return $header;
    }

    public function rules(): array
    {
        return [
            'item_code' => 'required|string|max:100|unique:item_headers,item_code',
            'item_name' => 'nullable|string',
            'capacity' => 'nullable|numeric|min:0',
            'qty' => 'nullable|integer|min:1',
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
