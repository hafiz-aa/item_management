<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerTypeRequest;
use App\Http\Requests\UpdateCustomerTypeRequest;
use App\Models\CustomerType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerTypeController extends Controller
{
    public function index(Request $request): View
    {
        $query = CustomerType::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('custtp_code', 'like', "%{$search}%")
                    ->orWhere('custtp_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('custtp_is_active', $request->status);
        }

        $customerTypes = $query->orderBy('custtp_code', 'asc')
            ->paginate($request->get('per_page', 25))
            ->withQueryString();

        $filters = $request->only(['search', 'status']);

        return view('settings.customer-type', compact('customerTypes', 'filters'));
    }

    public function create(): View
    {
        return view('settings.create-customer-type');
    }

    public function store(StoreCustomerTypeRequest $request): RedirectResponse
    {
        CustomerType::create($request->validated());

        return redirect()->route('customer-types.index')
            ->with('success', 'Customer type berhasil ditambahkan.');
    }

    public function edit(CustomerType $customerType): View
    {
        return view('settings.edit-customer-type', ['customerType' => $customerType]);
    }

    public function update(UpdateCustomerTypeRequest $request, CustomerType $customerType): RedirectResponse
    {
        $customerType->update($request->validated());

        return redirect()->route('customer-types.index')
            ->with('success', 'Customer type berhasil diupdate.');
    }

    public function destroy(CustomerType $customerType): RedirectResponse
    {
        $customerType->delete();

        return redirect()->route('customer-types.index')
            ->with('success', 'Customer type berhasil dihapus.');
    }
}
