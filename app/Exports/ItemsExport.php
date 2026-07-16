<?php

namespace App\Exports;

use App\Models\ItemHeader;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ItemsExport implements FromQuery, ShouldAutoSize, WithHeadings, WithMapping, WithStyles
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
            return ItemHeader::whereNull('itemh_id');
        }

        $query = ItemHeader::query()->with('details.warehouse');

        if (! empty($this->filters['warehouse_id'])) {
            $query->whereHas('details', function ($q) {
                $q->where('warehouse_id', $this->filters['warehouse_id']);
            });
        }

        if (! empty($this->filters['status'])) {
            $query->whereHas('details', function ($q) {
                $q->where('status', $this->filters['status']);
            });
        }

        if (! empty($this->filters['cat_id'])) {
            $query->where('cat_id', $this->filters['cat_id']);
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'Item Code',
            'Item Name',
            'Capacity',
            'UoM 1',
            'UoM 2',
            'Category',
            'Detail Code',
            'Qty',
            'Status',
            'Acquired Date',
            'Warehouse',
            'Broken',
            'Disposed',
            'Write Off',
        ];
    }

    public function map($item): array
    {
        $detail = $item->details->first();

        return [
            $item->item_code,
            $item->item_name,
            $item->capacity,
            $item->uom_id_1,
            $item->uom_id_2,
            $item->cat_id,
            $detail?->itemd_code,
            $detail?->qty,
            $detail?->status,
            $detail?->acquired_date?->format('Y-m-d'),
            $detail->warehouse?->nama_gudang ?? null,
            $detail?->is_broken ? 'Ya' : 'Tidak',
            $detail?->is_dispossed ? 'Ya' : 'Tidak',
            $detail?->is_writeoff ? 'Ya' : 'Tidak',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '0D6EFD']]],
        ];
    }
}
