<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWarehouseRequest;
use App\Http\Requests\UpdateWarehouseRequest;
use App\Models\Branch;
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
            ->where('whsl_type', '0')
            ->values()
            ->load('children.children.children');

        return view('warehouses.index', compact('warehouses'));
    }

    public function create(Request $request): View
    {
        $selectedParent = null;
        if ($request->filled('whsl_parent_id')) {
            $selectedParent = Warehouse::find($request->whsl_parent_id);
        }

        $parentWarehouses = Warehouse::active()->get();
        $branches = Branch::orderBy('branch_code')->get();

        return view('warehouses.create', compact('parentWarehouses', 'selectedParent', 'branches'));
    }

    public function store(StoreWarehouseRequest $request): RedirectResponse
    {
        $warehouse = $this->warehouseService->create($request->validated());

        if ($warehouse->whsl_parent_id) {
            return redirect()->route('warehouses.edit', $warehouse->whsl_parent_id)
                ->with('success', 'Data kantor cabang berhasil ditambahkan.');
        }

        return redirect()->route('warehouses.index')
            ->with('success', 'Data kantor cabang berhasil ditambahkan.');
    }

    public function edit(Warehouse $warehouse): View
    {
        $warehouse->load('children.children');
        $parentWarehouses = Warehouse::active()
            ->where('whsl_id', '!=', $warehouse->whsl_id)
            ->whereNotIn('whsl_id', $warehouse->children->pluck('whsl_id'))
            ->get();
        $branches = Branch::orderBy('branch_code')->get();

        return view('warehouses.edit', compact('warehouse', 'parentWarehouses', 'branches'));
    }

    public function update(UpdateWarehouseRequest $request, Warehouse $warehouse): RedirectResponse
    {
        $this->warehouseService->update($warehouse, $request->validated());

        if ($warehouse->whsl_parent_id) {
            return redirect()->route('warehouses.edit', $warehouse->whsl_parent_id)
                ->with('success', 'Data kantor cabang berhasil diupdate.');
        }

        return redirect()->route('warehouses.index')
            ->with('success', 'Data kantor cabang berhasil diupdate.');
    }

    public function destroy(Warehouse $warehouse): RedirectResponse
    {
        $parentId = $warehouse->whsl_parent_id;
        $this->warehouseService->delete($warehouse);

        if ($parentId) {
            return redirect()->route('warehouses.edit', $parentId)
                ->with('success', 'Data kantor cabang berhasil dihapus.');
        }

        return redirect()->route('warehouses.index')
            ->with('success', 'Data kantor cabang berhasil dihapus.');
    }
}
