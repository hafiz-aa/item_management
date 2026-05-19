<?php

namespace App\Exports;

use App\Models\Item;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ItemsExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    private array $filters;
    private bool $isTemplate;

    public function __construct(array $filters = [], bool $isTemplate = false)
    {
        $this->filters = $filters;
        $this->isTemplate = $isTemplate;
    }

    public function query()
    {
        if ($this->isTemplate) {
            return Item::whereNull('id');
        }

        $query = Item::query()->with('warehouse');

        if (!empty($this->filters['warehouse_id'])) {
            $query->where('lokasi_gudang_id', $this->filters['warehouse_id']);
        }

        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        if (!empty($this->filters['kategori'])) {
            $query->where('kategori', $this->filters['kategori']);
        }

        if (!empty($this->filters['vendor'])) {
            $query->where('vendor', $this->filters['vendor']);
        }

        if (!empty($this->filters['tahun_pembuatan'])) {
            $query->where('tahun_pembuatan', $this->filters['tahun_pembuatan']);
        }

        if (isset($this->filters['rusak'])) {
            $query->where('rusak', filter_var($this->filters['rusak'], FILTER_VALIDATE_BOOLEAN));
        }

        if (isset($this->filters['dijual'])) {
            $query->where('dijual', filter_var($this->filters['dijual'], FILTER_VALIDATE_BOOLEAN));
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'Kode Tabung',
            'Deskripsi Isi',
            'Serial No',
            'Tahun Pembuatan',
            'Berat',
            'Kapasitas',
            'UoM',
            'Qty',
            'Tanggal Perolehan',
            'Kategori',
            'Status',
            'Rusak',
            'Dijual',
            'Lokasi Gudang',
            'Vendor',
            'Pemilik Tabung',
        ];
    }

    public function map($item): array
    {
        return [
            $item->kode_tabung,
            $item->deskripsi_isi_tabung,
            $item->serial_no,
            $item->tahun_pembuatan,
            $item->berat,
            $item->kapasitas,
            $item->uom,
            $item->qty,
            $item->tanggal_perolehan?->format('Y-m-d'),
            $item->kategori,
            $item->status,
            $item->rusak ? 'Ya' : 'Tidak',
            $item->dijual ? 'Ya' : 'Tidak',
            $item->warehouse?->nama_gudang,
            $item->vendor,
            $item->pemilik_tabung,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '0D6EFD']]],
        ];
    }
}
