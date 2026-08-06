<?php

namespace App\Http\Controllers;

use App\Models\DisposalHeader;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DisposalController extends Controller
{
    public function index(Request $request): View
    {
        $query = DisposalHeader::query()
            ->leftJoin('broken_header', 'dispossal_header.brokh_id', '=', 'broken_header.brokh_id')
            ->leftJoin('customer', 'dispossal_header.cust_id', '=', 'customer.cust_id')
            ->select('dispossal_header.*', 'broken_header.brokh_code', 'customer.cust_name');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('dispossal_header.disph_code', 'like', "%{$search}%")
                    ->orWhere('dispossal_header.disph_notes', 'like', "%{$search}%")
                    ->orWhere('broken_header.brokh_code', 'like', "%{$search}%")
                    ->orWhere('customer.cust_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('source')) {
            $query->where('dispossal_header.disph_sources', $request->source);
        }

        if ($request->filled('canceled')) {
            $query->where('dispossal_header.disph_is_canceled', $request->canceled);
        }

        $branchFilter = session('branch_filter');
        if ($branchFilter) {
            $query->where('dispossal_header.branch_id', $branchFilter);
        }

        $disposals = $query->orderBy('dispossal_header.disph_date', 'desc')
            ->orderBy('dispossal_header.disph_id', 'desc')
            ->paginate($request->get('per_page', 25))
            ->withQueryString();

        $filters = $request->only(['search', 'source', 'canceled']);

        return view('transactions.disposal', compact('disposals', 'filters'));
    }
}
