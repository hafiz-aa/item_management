<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVendorRequest;
use App\Http\Requests\UpdateVendorRequest;
use App\Models\Customer;
use App\Models\CustomerType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VendorController extends Controller
{
    public function index(Request $request): View
    {
        $query = Customer::query()
            ->leftJoin('customer_type', 'customer_type.custtp_id', '=', 'customer.custtp_id')
            ->where('customer.cust_type', '1');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('customer.cust_code', 'like', "%{$search}%")
                    ->orWhere('customer.cust_name', 'like', "%{$search}%")
                    ->orWhere('customer.cust_email', 'like', "%{$search}%")
                    ->orWhere('customer.cust_phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('customer.cust_status', $request->status);
        }

        if ($request->filled('custtp_id')) {
            $query->where('customer.custtp_id', $request->custtp_id);
        }

        $vendors = $query->orderBy('customer.cust_code', 'asc')
            ->paginate($request->get('per_page', 25))
            ->withQueryString();

        $customerTypes = CustomerType::orderBy('custtp_code')->get();
        $filters = $request->only(['search', 'status', 'custtp_id']);

        return view('settings.vendor', compact('vendors', 'customerTypes', 'filters'));
    }

    public function create(): View
    {
        $customerTypes = CustomerType::orderBy('custtp_code')->get();

        return view('settings.create-vendor', compact('customerTypes'));
    }

    public function store(StoreVendorRequest $request): RedirectResponse
    {
        Customer::create(array_merge($request->validated(), ['cust_type' => '1']));

        return redirect()->route('vendors.index')
            ->with('success', 'Vendor berhasil ditambahkan.');
    }

    public function edit(Customer $customer): View
    {
        $customerTypes = CustomerType::orderBy('custtp_code')->get();

        return view('settings.edit-vendor', compact('customer', 'customerTypes'));
    }

    public function update(UpdateVendorRequest $request, Customer $customer): RedirectResponse
    {
        $customer->update($request->validated());

        return redirect()->route('vendors.index')
            ->with('success', 'Vendor berhasil diupdate.');
    }

    public function destroy(Customer $customer): RedirectResponse
    {
        $customer->delete();

        return redirect()->route('vendors.index')
            ->with('success', 'Vendor berhasil dihapus.');
    }
}
