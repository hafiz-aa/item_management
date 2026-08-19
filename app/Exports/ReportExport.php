<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReportExport implements FromQuery, ShouldAutoSize, WithHeadings, WithMapping, WithStyles
{
    private string $reportType;

    private $query;

    private const HEADINGS = [
        'transfer' => ['Kode Transfer', 'Tanggal', 'Status', 'Dibatalkan', 'Catatan', 'Kode Item', 'Serial No', 'Nama Item', 'Gudang Asal', 'Cabang Asal', 'Cabang Tujuan'],
        'issuing' => ['Kode Issuing', 'Tanggal', 'Tipe', 'Status', 'Dibatalkan', 'Customer', 'Kode Item', 'Serial No', 'Nama Item', 'Qty', 'Catatan'],
        'returning' => ['Kode Return', 'Tanggal', 'Ref Issuing', 'Kode Item', 'Serial No', 'Nama Item', 'Qty', 'Gudang', 'Dibatalkan', 'Catatan'],
        'broken' => ['Kode Broken', 'Tanggal', 'Status', 'Dibatalkan', 'Catatan', 'Kode Item', 'Serial No', 'Nama Item', 'Qty', 'WO', 'Disposal', 'Catatan Item'],
        'write-off' => ['Kode Write-Off', 'Tanggal', 'Sumber', 'Dibatalkan', 'Catatan', 'Kode Item', 'Serial No', 'Nama Item', 'Qty', 'Catatan Item'],
        'disposal' => ['Kode Disposal', 'Tanggal', 'Sumber', 'Dibatalkan', 'Catatan', 'Customer', 'Kode Item', 'Serial No', 'Nama Item', 'Qty', 'Catatan Item'],
        'position' => ['Kode Item', 'Serial No', 'Kapasitas', 'Qty', 'Nama Item', 'UoM', 'Gudang', 'Cabang'],
        'aging' => ['Kode Item', 'Serial No', 'Tanggal Akuisisi', 'Umur (Hari)', 'Kategori Aging', 'Nama Item', 'Gudang', 'Cabang', 'Qty'],
        'vendor' => ['Kode Vendor', 'Nama Vendor', 'Email', 'Telepon', 'Alamat', 'Total Item', 'Total Qty'],
    ];

    public function __construct(string $reportType, $query)
    {
        $this->reportType = $reportType;
        $this->query = $query;
    }

    public function query()
    {
        return $this->query;
    }

    public function headings(): array
    {
        return self::HEADINGS[$this->reportType] ?? [];
    }

    public function map($row): array
    {
        return match ($this->reportType) {
            'transfer' => [
                $row->tth_code,
                $row->tth_date,
                $row->tth_status,
                $row->tth_is_canceled == '1' ? 'Ya' : 'Tidak',
                $row->tth_notes,
                $row->itemd_code,
                $row->itemd_serial_no,
                $row->masti_name,
                $row->wh_from,
                $row->branch_from,
                $row->branch_to,
            ],
            'issuing' => [
                $row->issuingh_code,
                $row->issuingh_date,
                $row->issuingh_type,
                $row->issuingh_status,
                $row->issuingh_is_canceled == '1' ? 'Ya' : 'Tidak',
                $row->cust_name,
                $row->itemd_code,
                $row->itemd_serial_no,
                $row->masti_name,
                $row->issuingd_qty,
                $row->issuingd_notes,
            ],
            'returning' => [
                $row->reth_code,
                $row->reth_date,
                $row->ref_issue_code,
                $row->itemd_code,
                $row->itemd_serial_no,
                $row->masti_name,
                $row->retd_qty,
                $row->whsl_name,
                $row->reth_is_canceled == '1' ? 'Ya' : 'Tidak',
                $row->retd_notes,
            ],
            'broken' => [
                $row->brokh_code,
                $row->brokh_date,
                $row->brokh_status,
                $row->brokh_is_canceled == '1' ? 'Ya' : 'Tidak',
                $row->brokh_notes,
                $row->itemd_code,
                $row->itemd_serial_no,
                $row->masti_name,
                $row->brokd_qty,
                $row->brokd_is_wo == '1' ? 'Ya' : 'Tidak',
                $row->brokd_is_dispossed == '1' ? 'Ya' : 'Tidak',
                $row->brokd_notes,
            ],
            'write-off' => [
                $row->woh_code,
                $row->woh_date,
                $row->woh_sources,
                $row->woh_is_canceled == '1' ? 'Ya' : 'Tidak',
                $row->woh_notes,
                $row->itemd_code,
                $row->itemd_serial_no,
                $row->masti_name,
                $row->wod_qty,
                $row->wod_notes,
            ],
            'disposal' => [
                $row->disph_code,
                $row->disph_date,
                $row->disph_sources,
                $row->disph_is_canceled == '1' ? 'Ya' : 'Tidak',
                $row->disph_notes,
                $row->cust_name,
                $row->itemd_code,
                $row->itemd_serial_no,
                $row->masti_name,
                $row->dispd_qty,
                $row->dispd_notes,
            ],
            'position' => [
                $row->itemd_code,
                $row->itemd_serial_no,
                $row->itemd_capacity,
                $row->itemd_qty,
                $row->masti_name,
                $row->uom_name,
                $row->whsl_name,
                $row->branch_name,
            ],
            'aging' => $this->mapAging($row),
            'vendor' => [
                $row->cust_code,
                $row->cust_name,
                $row->cust_email,
                $row->cust_phone,
                $row->cust_address,
                $row->total_items ?? 0,
                $row->total_qty ?? 0,
            ],
            default => [],
        };
    }

    private function mapAging($row): array
    {
        $acquired = $row->itemd_acquired_date ? Carbon::parse($row->itemd_acquired_date) : null;
        $days = $acquired ? (int) $acquired->diffInDays(now()) : null;
        $bucket = match (true) {
            $days === null => '-',
            $days <= 30 => '1. ≤ 30 Hari',
            $days <= 60 => '2. 31 - 60 Hari',
            $days <= 90 => '3. 61 - 90 Hari',
            $days <= 180 => '4. 91 - 180 Hari',
            default => '5. > 180 Hari',
        };

        return [
            $row->itemd_code,
            $row->itemd_serial_no,
            $acquired?->format('Y-m-d'),
            $days,
            $bucket,
            $row->masti_name,
            $row->whsl_name,
            $row->branch_name,
            $row->itemd_qty,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '0D6EFD']],
            ],
        ];
    }
}
