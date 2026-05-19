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
        $warehouses = $this->warehouseService->getForUser($request->user());
        return view('warehouses.index', compact('warehouses'));
    }

    public function create(): View
    {
        return view('warehouses.create');
    }

    public function store(StoreWarehouseRequest $request): RedirectResponse
    {
        $this->warehouseService->create($request->validated());

        return redirect()->route('warehouses.index')
            ->with('success', 'Gudang berhasil ditambahkan.');
    }

    public function edit(Warehouse $warehouse): View
    {
        return view('warehouses.edit', compact('warehouse'));
    }

    public function update(UpdateWarehouseRequest $request, Warehouse $warehouse): RedirectResponse
    {
        $this->warehouseService->update($warehouse, $request->validated());

        return redirect()->route('warehouses.index')
            ->with('success', 'Gudang berhasil diupdate.');
    }

    public function destroy(Warehouse $warehouse): RedirectResponse
    {
        $this->warehouseService->delete($warehouse);

        return redirect()->route('warehouses.index')
            ->with('success', 'Gudang berhasil dihapus.');
    }
}
