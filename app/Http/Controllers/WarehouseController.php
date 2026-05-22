<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWarehouseRequest;
use App\Http\Requests\UpdateWarehouseRequest;
use App\Models\Warehouse;
use App\Services\WarehouseService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WarehouseController extends Controller
{
    private WarehouseService $warehouseService;

    public function __construct(WarehouseService $warehouseService)
    {
        $this->warehouseService = $warehouseService;
    }

    public function index(Request $request): View
    {
        $warehouses = $this->warehouseService->getForUser($request->user())
            ->where('tipe', 'Kantor Pusat')
            ->values()
            ->load('children.children.children');
        return view('warehouses.index', compact('warehouses'));
    }

    public function create(Request $request): View
    {
        $selectedParent = null;
        if ($request->filled('parent_id')) {
            $selectedParent = Warehouse::find($request->parent_id);
        }

        $parentWarehouses = Warehouse::active()->get();

        return view('warehouses.create', compact('parentWarehouses', 'selectedParent'));
    }

    public function store(StoreWarehouseRequest $request): RedirectResponse
    {
        $warehouse = $this->warehouseService->create($request->validated());

        if ($warehouse->parent_id) {
            return redirect()->route('warehouses.edit', $warehouse->parent_id)
                ->with('success', 'Data kantor cabang berhasil ditambahkan.');
        }

        return redirect()->route('warehouses.index')
            ->with('success', 'Data kantor cabang berhasil ditambahkan.');
    }

    public function edit(Warehouse $warehouse): View
    {
        $warehouse->load('children.children');
        $parentWarehouses = Warehouse::active()
            ->where('id', '!=', $warehouse->id)
            ->whereNotIn('id', $warehouse->children->pluck('id'))
            ->get();
        return view('warehouses.edit', compact('warehouse', 'parentWarehouses'));
    }

    public function update(UpdateWarehouseRequest $request, Warehouse $warehouse): RedirectResponse
    {
        $this->warehouseService->update($warehouse, $request->validated());

        if ($warehouse->parent_id) {
            return redirect()->route('warehouses.edit', $warehouse->parent_id)
                ->with('success', 'Data kantor cabang berhasil diupdate.');
        }

        return redirect()->route('warehouses.index')
            ->with('success', 'Data kantor cabang berhasil diupdate.');
    }

    public function destroy(Warehouse $warehouse): RedirectResponse
    {
        $parentId = $warehouse->parent_id;
        $this->warehouseService->delete($warehouse);

        if ($parentId) {
            return redirect()->route('warehouses.edit', $parentId)
                ->with('success', 'Data kantor cabang berhasil dihapus.');
        }

        return redirect()->route('warehouses.index')
            ->with('success', 'Data kantor cabang berhasil dihapus.');
    }
}
