<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\BrokenHeader;
use App\Models\Customer;
use App\Models\DisposalHeader;
use App\Models\Employee;
use App\Models\ItemDetail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DisposalController extends Controller
{
    public function index(Request $request): View
    {
        $query = DisposalHeader::query()
            ->leftJoin('broken_header', 'dispossal_header.brokh_id', '=', 'broken_header.brokh_id')
            ->leftJoin('customer', 'dispossal_header.cust_id', '=', 'customer.cust_id')
            ->select('dispossal_header.*', 'broken_header.brokh_code', 'customer.cust_name')
            ->withCount(['details as total_qty']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('dispossal_header.disph_code', 'like', "%{$search}%")
                    ->orWhere('dispossal_header.disph_notes', 'like', "%{$search}%")
                    ->orWhere('broken_header.brokh_code', 'like', "%{$search}%")
                    ->orWhere('customer.cust_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('source')) {
            $query->where('dispossal_header.disph_sources', $request->source);
        }

        if ($request->filled('canceled')) {
            $query->where('dispossal_header.disph_is_canceled', $request->canceled);
        }

        $branchFilter = session('branch_filter');
        if ($branchFilter) {
            $query->where('dispossal_header.branch_id', $branchFilter);
        }

        $disposals = $query->orderBy('dispossal_header.disph_date', 'desc')
            ->orderBy('dispossal_header.disph_id', 'desc')
            ->paginate($request->get('per_page', 25))
            ->withQueryString();

        $filters = $request->only(['search', 'source', 'canceled']);

        return view('transactions.disposal', compact('disposals', 'filters'));
    }

    public function show(DisposalHeader $disposal): View
    {
        $disposal->load([
            'details.itemDetail.header.category',
            'details.itemDetail.header.uom',
            'details.itemDetail.uom',
            'branch',
            'customer',
            'brokenHeader',
            'disposedBy',
            'creator',
        ]);

        return view('transactions.disposal-show', compact('disposal'));
    }

    public function create(): View
    {
        $branches = Branch::orderBy('branch_code')->get();
        $customers = Customer::orderBy('cust_name')->get();
        $employees = Employee::orderBy('emp_code')->get();
        $brokenHeaders = BrokenHeader::where('brokh_is_canceled', '0')
            ->orderBy('brokh_date', 'desc')
            ->get();

        return view('transactions.disposal-create', compact('branches', 'customers', 'employees', 'brokenHeaders'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'branch_id' => 'required|integer|exists:branch,branch_id',
            'disph_date' => 'required|date',
            'disph_sources' => 'required|in:0,1',
            'brokh_id' => 'nullable|integer|exists:broken_header,brokh_id',
            'cust_id' => 'required|integer|exists:customer,cust_id',
            'emp_id_dispossed_by' => 'nullable|integer|exists:employee,emp_id',
            'disph_reason' => 'nullable|string|max:255',
            'disph_notes' => 'nullable|string|max:255',
            'details' => 'nullable|array',
            'details.*.itemd_id' => 'required_with:details|integer|exists:item_detail,itemd_id',
            'details.*.dispd_qty' => 'required_with:details|integer|min:1',
            'details.*.dispd_notes' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            $disposal = DisposalHeader::create([
                'comp_id' => 0,
                'branch_id' => $request->branch_id,
                'disph_code' => $this->generateCode(),
                'disph_date' => $request->disph_date,
                'disph_sources' => $request->disph_sources,
                'brokh_id' => $request->brokh_id ?? 0,
                'disph_reason' => $request->disph_reason,
                'cust_id' => $request->cust_id,
                'emp_id_dispossed_by' => $request->emp_id_dispossed_by,
                'disph_is_canceled' => '0',
                'disph_notes' => $request->disph_notes,
                'created_by' => auth()->id(),
                'created_time' => now(),
            ]);

            if ($request->has('details')) {
                foreach ($request->details as $detail) {
                    $disposal->details()->create([
                        'brokd_id' => 0,
                        'itemd_id' => $detail['itemd_id'],
                        'dispd_qty' => $detail['dispd_qty'],
                        'dispd_is_canceled' => '0',
                        'dispd_notes' => $detail['dispd_notes'] ?? '',
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('transactions.disposal.show', $disposal)
                ->with('success', 'Disposal berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->withInput()
                ->with('error', 'Gagal menambahkan disposal: '.$e->getMessage());
        }
    }

    public function edit(DisposalHeader $disposal): View
    {
        $disposal->load(['details.itemDetail.header']);
        $branches = Branch::orderBy('branch_code')->get();
        $customers = Customer::orderBy('cust_name')->get();
        $employees = Employee::orderBy('emp_code')->get();
        $brokenHeaders = BrokenHeader::orderBy('brokh_date', 'desc')->get();

        return view('transactions.disposal-edit', compact('disposal', 'branches', 'customers', 'employees', 'brokenHeaders'));
    }

    public function update(Request $request, DisposalHeader $disposal): RedirectResponse
    {
        $request->validate([
            'branch_id' => 'required|integer|exists:branch,branch_id',
            'disph_date' => 'required|date',
            'disph_sources' => 'required|in:0,1',
            'brokh_id' => 'nullable|integer|exists:broken_header,brokh_id',
            'cust_id' => 'required|integer|exists:customer,cust_id',
            'emp_id_dispossed_by' => 'nullable|integer|exists:employee,emp_id',
            'disph_reason' => 'nullable|string|max:255',
            'disph_notes' => 'nullable|string|max:255',
            'details' => 'nullable|array',
            'details.*.itemd_id' => 'required_with:details|integer|exists:item_detail,itemd_id',
            'details.*.dispd_qty' => 'required_with:details|integer|min:1',
            'details.*.dispd_notes' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            $disposal->update([
                'branch_id' => $request->branch_id,
                'disph_date' => $request->disph_date,
                'disph_sources' => $request->disph_sources,
                'brokh_id' => $request->brokh_id ?? 0,
                'disph_reason' => $request->disph_reason,
                'cust_id' => $request->cust_id,
                'emp_id_dispossed_by' => $request->emp_id_dispossed_by,
                'disph_notes' => $request->disph_notes,
                'updated_by' => auth()->id(),
                'updated_time' => now(),
            ]);

            $disposal->details()->delete();

            if ($request->has('details')) {
                foreach ($request->details as $detail) {
                    $disposal->details()->create([
                        'brokd_id' => 0,
                        'itemd_id' => $detail['itemd_id'],
                        'dispd_qty' => $detail['dispd_qty'],
                        'dispd_is_canceled' => '0',
                        'dispd_notes' => $detail['dispd_notes'] ?? '',
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('transactions.disposal.show', $disposal)
                ->with('success', 'Disposal berhasil diupdate.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->withInput()
                ->with('error', 'Gagal mengupdate disposal: '.$e->getMessage());
        }
    }

    public function destroy(DisposalHeader $disposal): RedirectResponse
    {
        DB::beginTransaction();

        try {
            $disposal->details()->delete();
            $disposal->delete();
            DB::commit();

            return redirect()->route('transactions.disposal.index')
                ->with('success', 'Disposal berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Gagal menghapus disposal: '.$e->getMessage());
        }
    }

    public function itemDetails(Request $request): JsonResponse
    {
        $search = $request->get('search');
        $branchFilter = session('branch_filter');

        $query = ItemDetail::query()
            ->with('header')
            ->where('item_detail.itemd_status', '0');

        if ($branchFilter) {
            $query->where('item_detail.branch_id', $branchFilter);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('item_detail.itemd_code', 'like', "%{$search}%")
                    ->orWhere('item_detail.itemd_serial_no', 'like', "%{$search}%")
                    ->orWhereHas('header', function ($q2) use ($search) {
                        $q2->where('master_item.masti_code', 'like', "%{$search}%")
                            ->orWhere('master_item.masti_name', 'like', "%{$search}%");
                    });
            });
        }

        $items = $query->orderBy('item_detail.itemd_code')
            ->limit(10)
            ->get();

        return response()->json($items->map(function ($item) {
            return [
                'itemd_id' => $item->itemd_id,
                'itemd_code' => $item->itemd_code,
                'masti_id' => $item->masti_id,
                'masti_code' => $item->header->masti_code ?? '',
                'masti_name' => $item->header->masti_name ?? '',
                'itemd_serial_no' => $item->itemd_serial_no,
            ];
        }));
    }

    private function generateCode(): string
    {
        $date = now()->format('dmY');
        $lastDisposal = DisposalHeader::where('disph_code', 'like', "DIPSII-{$date}%")
            ->orderBy('disph_code', 'desc')
            ->first();

        if ($lastDisposal) {
            $lastNumber = (int) substr($lastDisposal->disph_code, -7);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return "DIPSII-{$date}".str_pad($newNumber, 7, '0', STR_PAD_LEFT);
    }
}
