<?php

namespace App\Http\Controllers;

use App\Models\WriteOffHeader;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WriteOffController extends Controller
{
    public function index(Request $request): View
    {
        $query = WriteOffHeader::query()
            ->leftJoin('broken_header', 'write_off_header.brokh_id', '=', 'broken_header.brokh_id')
            ->select('write_off_header.*', 'broken_header.brokh_code');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('write_off_header.woh_code', 'like', "%{$search}%")
                    ->orWhere('write_off_header.woh_notes', 'like', "%{$search}%")
                    ->orWhere('broken_header.brokh_code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('source')) {
            $query->where('write_off_header.woh_sources', $request->source);
        }

        if ($request->filled('canceled')) {
            $query->where('write_off_header.woh_is_canceled', $request->canceled);
        }

        $branchFilter = session('branch_filter');
        if ($branchFilter) {
            $query->where('write_off_header.branch_id', $branchFilter);
        }

        $writeOffs = $query->orderBy('write_off_header.woh_date', 'desc')
            ->orderBy('write_off_header.woh_id', 'desc')
            ->paginate($request->get('per_page', 25))
            ->withQueryString();

        $filters = $request->only(['search', 'source', 'canceled']);

        return view('transactions.write-off', compact('writeOffs', 'filters'));
    }
}
