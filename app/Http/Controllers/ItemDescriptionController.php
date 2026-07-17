<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreItemDescriptionRequest;
use App\Http\Requests\UpdateItemDescriptionRequest;
use App\Models\ItemCategory;
use App\Models\ItemDescription;
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
        $filters = $request->only(['search', 'trashed', 'per_page']);

        $itemDescriptions = $this->itemDescriptionService->search($filters);

        return view('item-descriptions.index', compact('itemDescriptions', 'filters'));
    }

    public function create(): View
    {
        $categories = ItemCategory::orderBy('category_code')->get();

        return view('item-descriptions.create', compact('categories'));
    }

    public function store(StoreItemDescriptionRequest $request): RedirectResponse
    {
        $this->itemDescriptionService->create($request->validated());

        return redirect()->route('item-descriptions.index')
            ->with('success', 'Item description berhasil ditambahkan.');
    }

    public function edit(ItemDescription $itemDescription): View
    {
        $categories = ItemCategory::orderBy('category_code')->get();

        return view('item-descriptions.edit', ['itemDescription' => $itemDescription, 'categories' => $categories]);
    }

    public function update(UpdateItemDescriptionRequest $request, ItemDescription $itemDescription): RedirectResponse
    {
        $this->itemDescriptionService->update($itemDescription, $request->validated());

        return redirect()->route('item-descriptions.index')
            ->with('success', 'Item description berhasil diupdate.');
    }

    public function destroy(ItemDescription $itemDescription): RedirectResponse
    {
        $this->itemDescriptionService->delete($itemDescription);

        return redirect()->route('item-descriptions.index')
            ->with('success', 'Item description berhasil dihapus.');
    }

    public function restore(int $id): RedirectResponse
    {
        $this->itemDescriptionService->restore($id);

        return redirect()->route('item-descriptions.index')
            ->with('success', 'Item description berhasil dipulihkan.');
    }
}
