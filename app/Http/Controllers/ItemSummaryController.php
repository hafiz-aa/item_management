<?php

namespace App\Http\Controllers;

use App\Models\ItemCategory;
use App\Models\MasterItem;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ItemSummaryController extends Controller
{
    public function index(Request $request): View
    {
        $query = MasterItem::query()
            ->select(
                'master_item.masti_id',
                'master_item.masti_code',
                'master_item.masti_name',
                'master_item.masti_capacity',
                'category_item.cati_code',
                'category_item.cati_name',
                'uom.uom_code',
                'uom.uom_name',
            )
            ->leftJoin('category_item', 'category_item.cati_id', '=', 'master_item.cati_id')
            ->leftJoin('uom', 'uom.uom_id', '=', 'master_item.uom_id_1')
            ->withCount([
                'details as qty',
                'details as qty_active' => fn ($q) => $q->where('itemd_status', '0'),
                'details as qty_broken' => fn ($q) => $q->where('itemd_is_broken', '1'),
                'details as qty_writeoff' => fn ($q) => $q->where('itemd_is_wo', '1'),
                'details as qty_sold' => fn ($q) => $q->where('itemd_is_dispossed', '1'),
            ]);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('master_item.masti_code', 'like', "%{$search}%")
                    ->orWhere('master_item.masti_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('cati_id')) {
            $query->where('master_item.cati_id', $request->cati_id);
        }

        $items = $query->orderBy('master_item.masti_code', 'asc')
            ->paginate($request->get('per_page', 25))
            ->withQueryString();

        $categories = ItemCategory::orderBy('cati_code')->get();
        $filters = $request->only(['search', 'cati_id']);

        return view('items.summary', compact('items', 'categories', 'filters'));
    }
}
