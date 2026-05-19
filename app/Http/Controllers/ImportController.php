<?php

namespace App\Http\Controllers;

use App\Imports\ItemsImport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    public function create(): \Illuminate\View\View
    {
        return view('import.create');
    }

    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:10240',
        ]);

        $import = new ItemsImport();
        Excel::import($import, $request->file('file'));

        $result = $import->getResult();

        if ($result['failed'] > 0) {
            return redirect()->route('import.result')
                ->with('warning', "Import selesai. {$result['success']} berhasil, {$result['failed']} gagal.")
                ->with('import_errors', $result['errors']);
        }

        return redirect()->route('items.index')
            ->with('success', "{$result['success']} item berhasil diimport.");
    }

    public function result(): \Illuminate\View\View
    {
        return view('import.result');
    }
}
