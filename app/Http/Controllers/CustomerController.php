<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Customer;
use App\Models\CustomerType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(Request $request): View
    {
        $query = Customer::query()
            ->leftJoin('customer_type', 'customer_type.custtp_id', '=', 'customer.custtp_id');

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

        $customers = $query->orderBy('customer.cust_code', 'asc')
            ->paginate($request->get('per_page', 25))
            ->withQueryString();

        $customerTypes = CustomerType::orderBy('custtp_code')->get();
        $filters = $request->only(['search', 'status', 'custtp_id']);

        return view('settings.customer', compact('customers', 'customerTypes', 'filters'));
    }

    public function create(): View
    {
        $customerTypes = CustomerType::orderBy('custtp_code')->get();

        return view('settings.create-customer', compact('customerTypes'));
    }

    public function store(StoreCustomerRequest $request): RedirectResponse
    {
        Customer::create($request->validated());

        return redirect()->route('customers.index')
            ->with('success', 'Customer berhasil ditambahkan.');
    }

    public function edit(Customer $customer): View
    {
        $customerTypes = CustomerType::orderBy('custtp_code')->get();

        return view('settings.edit-customer', compact('customer', 'customerTypes'));
    }

    public function update(UpdateCustomerRequest $request, Customer $customer): RedirectResponse
    {
        $customer->update($request->validated());

        return redirect()->route('customers.index')
            ->with('success', 'Customer berhasil diupdate.');
    }

    public function destroy(Customer $customer): RedirectResponse
    {
        $customer->delete();

        return redirect()->route('customers.index')
            ->with('success', 'Customer berhasil dihapus.');
    }
}
