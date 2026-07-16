<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreItemRequest;
use App\Http\Requests\UpdateItemRequest;
use App\Models\Branch;
use App\Models\ItemHeader;
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
            'search', 'warehouse_id', 'status', 'cat_id',
            'sort_field', 'sort_order', 'per_page',
        ]);

        $user = $request->user();
        if (! $user->hasRole('Super Admin')) {
            $filters['warehouse_ids'] = $user->warehouses->pluck('warehouse_id')->toArray();
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

        return view('items.create', compact('warehouses', 'branches'));
    }

    public function store(StoreItemRequest $request): RedirectResponse
    {
        $this->itemService->create($request->validated());

        return redirect()->route('items.index')
            ->with('success', 'Item berhasil ditambahkan.');
    }

    public function show(ItemHeader $item): View
    {
        $item->load(['details.warehouse', 'details.branch', 'details.originalBranch', 'creator', 'updater', 'details.creator', 'details.updater']);

        return view('items.show', compact('item'));
    }

    public function edit(ItemHeader $item): View
    {
        $warehouses = $this->warehouseService->getActive();
        $branches = Branch::orderBy('branch_code')->get();
        $item->load('details');

        return view('items.edit', compact('item', 'warehouses', 'branches'));
    }

    public function update(UpdateItemRequest $request, ItemHeader $item): RedirectResponse
    {
        $this->itemService->update($item, $request->validated());

        return redirect()->route('items.index')
            ->with('success', 'Item berhasil diupdate.');
    }

    public function destroy(ItemHeader $item): RedirectResponse
    {
        $this->itemService->delete($item);

        return redirect()->route('items.index')
            ->with('success', 'Item berhasil dihapus.');
    }

    public function bulkDelete(Request $request): RedirectResponse
    {
        $request->validate(['ids' => 'required|array', 'ids.*' => 'exists:item_headers,itemh_id']);
        $this->itemService->bulkDelete($request->ids);

        return redirect()->route('items.index')
            ->with('success', count($request->ids).' item berhasil dihapus.');
    }
}
