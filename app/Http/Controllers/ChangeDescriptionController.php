<?php

namespace App\Http\Controllers;

use App\Models\ChangeItemDescriptionHeader;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChangeDescriptionController extends Controller
{
    public function index(Request $request): View
    {
        $query = ChangeItemDescriptionHeader::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('change_item_description_header.cidh_code', 'like', "%{$search}%")
                    ->orWhere('change_item_description_header.cidh_notes', 'like', "%{$search}%");
            });
        }

        if ($request->filled('canceled')) {
            $query->where('change_item_description_header.cidh_is_canceled', $request->canceled);
        }

        $branchFilter = session('branch_filter');
        if ($branchFilter) {
            $query->where('change_item_description_header.branch_id', $branchFilter);
        }

        $changes = $query->orderBy('change_item_description_header.cidh_date', 'desc')
            ->orderBy('change_item_description_header.cidh_id', 'desc')
            ->paginate($request->get('per_page', 25))
            ->withQueryString();

        $filters = $request->only(['search', 'canceled']);

        return view('transactions.change-description', compact('changes', 'filters'));
    }
}
