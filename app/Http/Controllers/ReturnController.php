<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Employee;
use App\Models\IssuingHeader;
use App\Models\ReturnHeader;
use App\Models\Warehouse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReturnController extends Controller
{
    public function index(Request $request): View
    {
        $query = ReturnHeader::query()
            ->with(['issuingHeader.customer', 'warehouse'])
            ->withCount(['details as total_item']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('return_header.reth_code', 'like', "%{$search}%")
                    ->orWhere('return_header.reth_ba_no', 'like', "%{$search}%")
                    ->orWhere('return_header.reth_po_no', 'like', "%{$search}%")
                    ->orWhere('return_header.reth_ref_no', 'like', "%{$search}%")
                    ->orWhereHas('issuingHeader', function ($q2) use ($search) {
                        $q2->where('issuingh_code', 'like', "%{$search}%");
                    })
                    ->orWhereHas('issuingHeader.customer', function ($q2) use ($search) {
                        $q2->where('cust_name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('return_header.reth_is_canceled', $request->status);
        }

        $branchFilter = session('branch_filter');
        if ($branchFilter) {
            $query->where('return_header.branch_id', $branchFilter);
        }

        $returns = $query->orderBy('return_header.reth_date', 'desc')
            ->orderBy('return_header.reth_id', 'desc')
            ->paginate($request->get('per_page', 25))
            ->withQueryString();

        $filters = $request->only(['search', 'status']);

        return view('transactions.return', compact('returns', 'filters'));
    }

    public function show(ReturnHeader $return): View
    {
        $return->load([
            'details.issuingDetail.itemDetail.header.category',
            'details.issuingDetail.itemDetail.header.uom',
            'details.issuingDetail.itemDetail.uom',
            'details.warehouseNew',
            'issuingHeader.customer',
            'issuingHeader.employee',
            'warehouse',
            'branch',
            'receiver',
            'creator',
        ]);

        return view('transactions.return-show', compact('return'));
    }

    public function create(): View
    {
        $branches = Branch::orderBy('branch_code')->get();
        $employees = Employee::orderBy('emp_code')->get();
        $warehouses = Warehouse::orderBy('whsl_code')->get();
        $issuings = IssuingHeader::orderBy('issuingh_date', 'desc')->get();

        return view('transactions.return-create', compact('branches', 'employees', 'warehouses', 'issuings'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'branch_id' => 'required|integer|exists:branch,branch_id',
            'issuingh_id' => 'required|integer|exists:issuing_header,issuingh_id',
            'reth_date' => 'required|date',
            'reth_ref_no' => 'nullable|string|max:50',
            'reth_ba_no' => 'nullable|string|max:50',
            'reth_po_no' => 'nullable|string|max:50',
            'emp_id_receiver' => 'required|integer|exists:employee,emp_id',
            'whsl_id' => 'nullable|integer|exists:warehouse_location,whsl_id',
            'reth_notes' => 'nullable|string|max:255',
            'details' => 'nullable|array',
            'details.*.issuingd_id' => 'required_with:details|integer|exists:issuing_detail,issuingd_id',
            'details.*.retd_qty' => 'required_with:details|numeric|min:0',
            'details.*.whsl_id' => 'required_with:details|integer|exists:warehouse_location,whsl_id',
            'details.*.retd_notes' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            $return = ReturnHeader::create([
                'comp_id' => 0,
                'branch_id' => $request->branch_id,
                'reth_code' => $this->generateCode(),
                'issuingh_id' => $request->issuingh_id,
                'reth_date' => $request->reth_date,
                'reth_ref_no' => $request->reth_ref_no,
                'reth_ba_no' => $request->reth_ba_no,
                'reth_po_no' => $request->reth_po_no,
                'emp_id_receiver' => $request->emp_id_receiver,
                'whsl_id' => $request->whsl_id,
                'reth_is_canceled' => '0',
                'reth_notes' => $request->reth_notes,
                'created_by' => auth()->id(),
                'created_time' => now(),
            ]);

            if ($request->has('details')) {
                foreach ($request->details as $detail) {
                    $return->details()->create([
                        'issuingd_id' => $detail['issuingd_id'],
                        'retd_qty' => $detail['retd_qty'],
                        'whsl_id' => $detail['whsl_id'],
                        'retd_is_canceled' => '0',
                        'retd_notes' => $detail['retd_notes'] ?? '',
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('transactions.return.show', $return)
                ->with('success', 'Return berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->withInput()
                ->with('error', 'Gagal menambahkan return: '.$e->getMessage());
        }
    }

    public function edit(ReturnHeader $return): View
    {
        $return->load('details');
        $branches = Branch::orderBy('branch_code')->get();
        $employees = Employee::orderBy('emp_code')->get();
        $warehouses = Warehouse::orderBy('whsl_code')->get();
        $issuings = IssuingHeader::orderBy('issuingh_date', 'desc')->get();

        return view('transactions.return-edit', compact('return', 'branches', 'employees', 'warehouses', 'issuings'));
    }

    public function update(Request $request, ReturnHeader $return): RedirectResponse
    {
        $request->validate([
            'branch_id' => 'required|integer|exists:branch,branch_id',
            'issuingh_id' => 'required|integer|exists:issuing_header,issuingh_id',
            'reth_date' => 'required|date',
            'reth_ref_no' => 'nullable|string|max:50',
            'reth_ba_no' => 'nullable|string|max:50',
            'reth_po_no' => 'nullable|string|max:50',
            'emp_id_receiver' => 'required|integer|exists:employee,emp_id',
            'whsl_id' => 'nullable|integer|exists:warehouse_location,whsl_id',
            'reth_notes' => 'nullable|string|max:255',
            'details' => 'nullable|array',
            'details.*.issuingd_id' => 'required_with:details|integer|exists:issuing_detail,issuingd_id',
            'details.*.retd_qty' => 'required_with:details|numeric|min:0',
            'details.*.whsl_id' => 'required_with:details|integer|exists:warehouse_location,whsl_id',
            'details.*.retd_notes' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            $return->update([
                'branch_id' => $request->branch_id,
                'issuingh_id' => $request->issuingh_id,
                'reth_date' => $request->reth_date,
                'reth_ref_no' => $request->reth_ref_no,
                'reth_ba_no' => $request->reth_ba_no,
                'reth_po_no' => $request->reth_po_no,
                'emp_id_receiver' => $request->emp_id_receiver,
                'whsl_id' => $request->whsl_id,
                'reth_notes' => $request->reth_notes,
                'updated_by' => auth()->id(),
                'updated_time' => now(),
            ]);

            $return->details()->delete();

            if ($request->has('details')) {
                foreach ($request->details as $detail) {
                    $return->details()->create([
                        'issuingd_id' => $detail['issuingd_id'],
                        'retd_qty' => $detail['retd_qty'],
                        'whsl_id' => $detail['whsl_id'],
                        'retd_is_canceled' => '0',
                        'retd_notes' => $detail['retd_notes'] ?? '',
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('transactions.return.show', $return)
                ->with('success', 'Return berhasil diupdate.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->withInput()
                ->with('error', 'Gagal mengupdate return: '.$e->getMessage());
        }
    }

    public function destroy(ReturnHeader $return): RedirectResponse
    {
        DB::beginTransaction();

        try {
            $return->details()->delete();
            $return->delete();
            DB::commit();

            return redirect()->route('transactions.return')
                ->with('success', 'Return berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Gagal menghapus return: '.$e->getMessage());
        }
    }

    private function generateCode(): string
    {
        $date = now()->format('dmY');
        $lastReturn = ReturnHeader::where('reth_code', 'like', "RETSII-{$date}%")
            ->orderBy('reth_code', 'desc')
            ->first();

        if ($lastReturn) {
            $lastNumber = (int) substr($lastReturn->reth_code, -7);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return "RETSII-{$date}".str_pad($newNumber, 7, '0', STR_PAD_LEFT);
    }
}
