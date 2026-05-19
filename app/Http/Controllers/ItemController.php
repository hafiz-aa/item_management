<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreItemRequest;
use App\Http\Requests\UpdateItemRequest;
use App\Models\Item;
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
            'search', 'warehouse_id', 'status', 'kategori',
            'vendor', 'tahun_pembuatan', 'rusak', 'dijual',
            'sort_field', 'sort_order', 'per_page'
        ]);

        $user = $request->user();
        if (!$user->hasRole('Super Admin')) {
            $filters['warehouse_ids'] = $user->warehouses->pluck('id')->toArray();
        }

        $items = $this->itemService->search($filters);
        $warehouses = $this->warehouseService->getForUser($user);
        $kategoris = $this->itemService->getKategoris();
        $vendors = $this->itemService->getVendors();

        return view('items.index', compact('items', 'warehouses', 'kategoris', 'vendors', 'filters'));
    }

    public function create(): View
    {
        $warehouses = $this->warehouseService->getActive();
        return view('items.create', compact('warehouses'));
    }

    public function store(StoreItemRequest $request): RedirectResponse
    {
        $this->itemService->create($request->validated());

        return redirect()->route('items.index')
            ->with('success', 'Tabung berhasil ditambahkan.');
    }

    public function show(Item $item): View
    {
        $item->load(['warehouse', 'creator', 'updater']);
        return view('items.show', compact('item'));
    }

    public function edit(Item $item): View
    {
        $warehouses = $this->warehouseService->getActive();
        return view('items.edit', compact('item', 'warehouses'));
    }

    public function update(UpdateItemRequest $request, Item $item): RedirectResponse
    {
        $this->itemService->update($item, $request->validated());

        return redirect()->route('items.index')
            ->with('success', 'Tabung berhasil diupdate.');
    }

    public function destroy(Item $item): RedirectResponse
    {
        $this->itemService->delete($item);

        return redirect()->route('items.index')
            ->with('success', 'Tabung berhasil dihapus.');
    }

    public function bulkDelete(Request $request): RedirectResponse
    {
        $request->validate(['ids' => 'required|array', 'ids.*' => 'exists:items,id']);
        $this->itemService->bulkDelete($request->ids);

        return redirect()->route('items.index')
            ->with('success', count($request->ids) . ' tabung berhasil dihapus.');
    }
}
