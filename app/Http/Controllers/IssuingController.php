<?php

namespace App\Http\Controllers;

use App\Models\IssuingHeader;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IssuingController extends Controller
{
    public function index(Request $request): View
    {
        $query = IssuingHeader::query()
            ->with(['customer', 'employee', 'branch'])
            ->withCount(['details as total_qty']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('issuing_header.issuingh_code', 'like', "%{$search}%")
                    ->orWhere('issuing_header.issuingh_ba_no', 'like', "%{$search}%")
                    ->orWhere('issuing_header.issuingh_po_no', 'like', "%{$search}%")
                    ->orWhere('issuing_header.issuingh_do_no', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($q2) use ($search) {
                        $q2->where('cust_name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('issuing_header.issuingh_is_canceled', $request->status);
        }

        $branchFilter = session('branch_filter');
        if ($branchFilter) {
            $query->where('issuing_header.branch_id', $branchFilter);
        }

        $issuings = $query->orderBy('issuing_header.issuingh_date', 'desc')
            ->orderBy('issuing_header.issuingh_id', 'desc')
            ->paginate($request->get('per_page', 25))
            ->withQueryString();

        $filters = $request->only(['search', 'status']);

        return view('transactions.issue', compact('issuings', 'filters'));
    }
}
