<?php

namespace App\Http\Controllers;

use App\Exports\ItemsExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExportController extends Controller
{
    public function export(Request $request): BinaryFileResponse
    {
        $filters = $request->only([
            'search', 'warehouse_id', 'status', 'kategori',
            'vendor', 'tahun_pembuatan', 'rusak', 'dijual'
        ]);

        return Excel::download(new ItemsExport($filters), 'items-' . date('Y-m-d') . '.xlsx');
    }

    public function template(): BinaryFileResponse
    {
        return Excel::download(new ItemsExport([], true), 'template-import-item.xlsx');
    }
}
