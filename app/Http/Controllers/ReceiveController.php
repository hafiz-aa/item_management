<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\ReceiptTransferHeader;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReceiveController extends Controller
{
    public function index(Request $request): View
    {
        $query = ReceiptTransferHeader::query()
            ->with(['branchFrom'])
            ->withCount(['details as total_qty']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('receipt_transfer_header.rth_code', 'like', "%{$search}%")
                    ->orWhere('receipt_transfer_header.rth_ba_no', 'like', "%{$search}%")
                    ->orWhere('receipt_transfer_header.rth_po_no', 'like', "%{$search}%")
                    ->orWhereHas('branchFrom', function ($q2) use ($search) {
                        $q2->where('branch_name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('receipt_transfer_header.rth_is_canceled', $request->status);
        }

        $receives = $query->orderBy('receipt_transfer_header.rth_date', 'desc')
            ->orderBy('receipt_transfer_header.rth_id', 'desc')
            ->paginate($request->get('per_page', 25))
            ->withQueryString();

        $branches = Branch::orderBy('branch_code')->get();
        $filters = $request->only(['search', 'status']);

        return view('transactions.receive', compact('receives', 'branches', 'filters'));
    }
}
