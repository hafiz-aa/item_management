<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreItemRequest;
use App\Http\Requests\UpdateItemRequest;
use App\Models\Branch;
use App\Models\ItemCategory;
use App\Models\MasterItem;
use App\Models\Uom;
use App\Services\ItemService;
use App\Services\WarehouseService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ItemController extends Controller
{
    private ItemService $itemService;

    private WarehouseService $warehouseService;

    public function __construct(ItemService $itemService, WarehouseService $warehouseService)
    {
        $this->itemService = $itemService;
        $this->warehouseService = $warehouseService;
    }

    public function index(Request $request): View
    {
        $filters = $request->only([
            'search', 'whsl_id', 'status', 'cati_id',
            'sort_field', 'sort_order', 'per_page',
        ]);

        $user = $request->user();
        if (! $user->hasRole('Super Admin')) {
            $filters['warehouse_ids'] = $user->branches->pluck('branch_id')->toArray();
        }

        $items = $this->itemService->search($filters);
        $warehouses = $this->warehouseService->getForUser($user);
        $catIds = $this->itemService->getCatIds();

        return view('items.index', compact('items', 'warehouses', 'catIds', 'filters'));
    }

    public function create(): View
    {
        $warehouses = $this->warehouseService->getActive();
        $branches = Branch::orderBy('branch_code')->get();
        $categories = ItemCategory::orderBy('cati_code')->get();
        $uoms = Uom::orderBy('uom_code')->get();

        return view('items.create', compact('warehouses', 'branches', 'categories', 'uoms'));
    }

    public function store(StoreItemRequest $request): RedirectResponse
    {
        $this->itemService->create($request->validated());

        return redirect()->route('items.index')
            ->with('success', 'Item berhasil ditambahkan.');
    }

    public function show(MasterItem $item): View
    {
        $item->load(['details.warehouse', 'details.branch', 'details.originalBranch', 'category', 'uom']);

        return view('items.show', compact('item'));
    }

    public function edit(MasterItem $item): View
    {
        $warehouses = $this->warehouseService->getActive();
        $branches = Branch::orderBy('branch_code')->get();
        $categories = ItemCategory::orderBy('cati_code')->get();
        $uoms = Uom::orderBy('uom_code')->get();
        $item->load('details');

        return view('items.edit', compact('item', 'warehouses', 'branches', 'categories', 'uoms'));
    }

    public function update(UpdateItemRequest $request, MasterItem $item): RedirectResponse
    {
        $this->itemService->update($item, $request->validated());

        return redirect()->route('items.index')
            ->with('success', 'Item berhasil diupdate.');
    }

    public function destroy(MasterItem $item): RedirectResponse
    {
        $this->itemService->delete($item);

        return redirect()->route('items.index')
            ->with('success', 'Item berhasil dihapus.');
    }

    public function bulkDelete(Request $request): RedirectResponse
    {
        $request->validate(['ids' => 'required|array', 'ids.*' => 'exists:master_item,masti_id']);
        $this->itemService->bulkDelete($request->ids);

        return redirect()->route('items.index')
            ->with('success', count($request->ids).' item berhasil dihapus.');
    }
}
