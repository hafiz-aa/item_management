<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreItemDescriptionRequest;
use App\Http\Requests\UpdateItemDescriptionRequest;
use App\Models\ItemCategory;
use App\Models\MasterItem;
use App\Models\Uom;
use App\Services\ItemDescriptionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ItemDescriptionController extends Controller
{
    private ItemDescriptionService $itemDescriptionService;

    public function __construct(ItemDescriptionService $itemDescriptionService)
    {
        $this->itemDescriptionService = $itemDescriptionService;
    }

    public function index(Request $request): View
    {
        $filters = $request->only(['search', 'per_page']);
        $itemDescriptions = $this->itemDescriptionService->search($filters);

        return view('item-descriptions.index', compact('itemDescriptions', 'filters'));
    }

    public function create(): View
    {
        $categories = ItemCategory::orderBy('cati_code')->get();
        $uoms = Uom::orderBy('uom_code')->get();

        return view('item-descriptions.create', compact('categories', 'uoms'));
    }

    public function store(StoreItemDescriptionRequest $request): RedirectResponse
    {
        $this->itemDescriptionService->create($request->validated());

        return redirect()->route('item-descriptions.index')
            ->with('success', 'Item description berhasil ditambahkan.');
    }

    public function edit(MasterItem $itemDescription): View
    {
        $categories = ItemCategory::orderBy('cati_code')->get();
        $uoms = Uom::orderBy('uom_code')->get();

        return view('item-descriptions.edit', ['itemDescription' => $itemDescription, 'categories' => $categories, 'uoms' => $uoms]);
    }

    public function update(UpdateItemDescriptionRequest $request, MasterItem $itemDescription): RedirectResponse
    {
        $this->itemDescriptionService->update($itemDescription, $request->validated());

        return redirect()->route('item-descriptions.index')
            ->with('success', 'Item description berhasil diupdate.');
    }

    public function destroy(MasterItem $itemDescription): RedirectResponse
    {
        $this->itemDescriptionService->delete($itemDescription);

        return redirect()->route('item-descriptions.index')
            ->with('success', 'Item description berhasil dihapus.');
    }
}
