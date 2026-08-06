<?php

namespace App\Http\Controllers;

use App\Models\BrokenHeader;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BrokenController extends Controller
{
    public function index(Request $request): View
    {
        $query = BrokenHeader::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('broken_header.brokh_code', 'like', "%{$search}%")
                    ->orWhere('broken_header.brokh_notes', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('broken_header.brokh_status', $request->status);
        }

        if ($request->filled('canceled')) {
            $query->where('broken_header.brokh_is_canceled', $request->canceled);
        }

        $branchFilter = session('branch_filter');
        if ($branchFilter) {
            $query->where('broken_header.branch_id', $branchFilter);
        }

        $brokens = $query->orderBy('broken_header.brokh_date', 'desc')
            ->orderBy('broken_header.brokh_id', 'desc')
            ->paginate($request->get('per_page', 25))
            ->withQueryString();

        $filters = $request->only(['search', 'status', 'canceled']);

        return view('transactions.broken', compact('brokens', 'filters'));
    }
}
