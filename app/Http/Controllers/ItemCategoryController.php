<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreItemCategoryRequest;
use App\Http\Requests\UpdateItemCategoryRequest;
use App\Models\ItemCategory;
use App\Services\ItemCategoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ItemCategoryController extends Controller
{
    private ItemCategoryService $itemCategoryService;

    public function __construct(ItemCategoryService $itemCategoryService)
    {
        $this->itemCategoryService = $itemCategoryService;
    }

    public function index(Request $request): View
    {
        $filters = $request->only(['search', 'per_page']);
        $categories = $this->itemCategoryService->search($filters);

        return view('item-categories.index', compact('categories', 'filters'));
    }

    public function create(): View
    {
        return view('item-categories.create');
    }

    public function store(StoreItemCategoryRequest $request): RedirectResponse
    {
        $this->itemCategoryService->create($request->validated());

        return redirect()->route('item-categories.index')
            ->with('success', 'Item category berhasil ditambahkan.');
    }

    public function edit(ItemCategory $itemCategory): View
    {
        return view('item-categories.edit', ['category' => $itemCategory]);
    }

    public function update(UpdateItemCategoryRequest $request, ItemCategory $itemCategory): RedirectResponse
    {
        $this->itemCategoryService->update($itemCategory, $request->validated());

        return redirect()->route('item-categories.index')
            ->with('success', 'Item category berhasil diupdate.');
    }

    public function destroy(ItemCategory $itemCategory): RedirectResponse
    {
        $this->itemCategoryService->delete($itemCategory);

        return redirect()->route('item-categories.index')
            ->with('success', 'Item category berhasil dihapus.');
    }
}
