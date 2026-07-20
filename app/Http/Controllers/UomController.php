<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUomRequest;
use App\Http\Requests\UpdateUomRequest;
use App\Models\Uom;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UomController extends Controller
{
    public function index(Request $request): View
    {
        $query = Uom::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('uom_code', 'like', "%{$search}%")
                    ->orWhere('uom_name', 'like', "%{$search}%");
            });
        }

        $uoms = $query->orderBy('uom_code', 'asc')
            ->paginate($request->get('per_page', 25))
            ->withQueryString();

        $filters = $request->only(['search']);

        return view('settings.uom', compact('uoms', 'filters'));
    }

    public function create(): View
    {
        return view('settings.create-uom');
    }

    public function store(StoreUomRequest $request): RedirectResponse
    {
        Uom::create($request->validated());

        return redirect()->route('uoms.index')
            ->with('success', 'Unit of measurement berhasil ditambahkan.');
    }

    public function edit(Uom $uom): View
    {
        return view('settings.edit-uom', compact('uom'));
    }

    public function update(UpdateUomRequest $request, Uom $uom): RedirectResponse
    {
        $uom->update($request->validated());

        return redirect()->route('uoms.index')
            ->with('success', 'Unit of measurement berhasil diupdate.');
    }

    public function destroy(Uom $uom): RedirectResponse
    {
        $uom->delete();

        return redirect()->route('uoms.index')
            ->with('success', 'Unit of measurement berhasil dihapus.');
    }
}
