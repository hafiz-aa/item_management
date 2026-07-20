<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\ItemCategory;
use App\Models\ItemDetail;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ItemAllBranchesController extends Controller
{
    public function index(Request $request): View
    {
        $query = ItemDetail::query()
            ->select(
                'item_detail.itemd_id',
                'item_detail.itemd_code',
                'item_detail.itemd_serial_no',
                'item_detail.itemd_capacity',
                'item_detail.itemd_qty',
                'item_detail.itemd_acquired_date',
                'item_detail.itemd_status',
                'master_item.masti_code',
                'master_item.masti_name',
                'category_item.cati_code',
                'category_item.cati_name',
                'branch.branch_code',
                'branch.branch_name',
                'uom.uom_code',
                'uom.uom_name',
            )
            ->join('master_item', 'master_item.masti_id', '=', 'item_detail.masti_id')
            ->leftJoin('category_item', 'category_item.cati_id', '=', 'master_item.cati_id')
            ->leftJoin('branch', 'branch.branch_id', '=', 'item_detail.branch_id')
            ->leftJoin('uom', 'uom.uom_id', '=', 'item_detail.uom_id');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('master_item.masti_code', 'like', "%{$search}%")
                    ->orWhere('master_item.masti_name', 'like', "%{$search}%")
                    ->orWhere('item_detail.itemd_code', 'like', "%{$search}%")
                    ->orWhere('item_detail.itemd_serial_no', 'like', "%{$search}%")
                    ->orWhere('branch.branch_code', 'like', "%{$search}%")
                    ->orWhere('branch.branch_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('branch_id')) {
            $query->where('item_detail.branch_id', $request->branch_id);
        }

        if ($request->filled('status')) {
            $query->where('item_detail.itemd_status', $request->status);
        }

        if ($request->filled('cati_id')) {
            $query->where('master_item.cati_id', $request->cati_id);
        }

        $items = $query->orderBy('branch.branch_code', 'asc')
            ->orderBy('master_item.masti_code', 'asc')
            ->paginate($request->get('per_page', 25))
            ->withQueryString();

        $branches = Branch::orderBy('branch_code')->get();
        $categories = ItemCategory::orderBy('cati_code')->get();

        $filters = $request->only(['search', 'branch_id', 'status', 'cati_id']);

        return view('items.all-branches', compact('items', 'branches', 'categories', 'filters'));
    }
}
