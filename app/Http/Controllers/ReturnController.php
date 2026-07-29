<?php

namespace App\Http\Controllers;

use App\Models\ReturnHeader;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReturnController extends Controller
{
    public function index(Request $request): View
    {
        $query = ReturnHeader::query()
            ->with(['issuingHeader.customer', 'warehouse'])
            ->withCount(['details as total_item']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('return_header.reth_code', 'like', "%{$search}%")
                    ->orWhere('return_header.reth_ba_no', 'like', "%{$search}%")
                    ->orWhere('return_header.reth_po_no', 'like', "%{$search}%")
                    ->orWhere('return_header.reth_ref_no', 'like', "%{$search}%")
                    ->orWhereHas('issuingHeader', function ($q2) use ($search) {
                        $q2->where('issuingh_code', 'like', "%{$search}%");
                    })
                    ->orWhereHas('issuingHeader.customer', function ($q2) use ($search) {
                        $q2->where('cust_name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('return_header.reth_is_canceled', $request->status);
        }

        $branchFilter = session('branch_filter');
        if ($branchFilter) {
            $query->where('return_header.branch_id', $branchFilter);
        }

        $returns = $query->orderBy('return_header.reth_date', 'desc')
            ->orderBy('return_header.reth_id', 'desc')
            ->paginate($request->get('per_page', 25))
            ->withQueryString();

        $filters = $request->only(['search', 'status']);

        return view('transactions.return', compact('returns', 'filters'));
    }
}
