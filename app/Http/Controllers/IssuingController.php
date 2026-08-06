<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\IssuingHeader;
use App\Models\Warehouse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class IssuingController extends Controller
{
    public function index(Request $request): View
    {
        $query = IssuingHeader::query()
            ->with(['customer', 'employee', 'branch'])
            ->withCount(['details as total_qty']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('issuing_header.issuingh_code', 'like', "%{$search}%")
                    ->orWhere('issuing_header.issuingh_ba_no', 'like', "%{$search}%")
                    ->orWhere('issuing_header.issuingh_po_no', 'like', "%{$search}%")
                    ->orWhere('issuing_header.issuingh_do_no', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($q2) use ($search) {
                        $q2->where('cust_name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('issuing_header.issuingh_is_canceled', $request->status);
        }

        $branchFilter = session('branch_filter');
        if ($branchFilter) {
            $query->where('issuing_header.branch_id', $branchFilter);
        }

        $issuings = $query->orderBy('issuing_header.issuingh_date', 'desc')
            ->orderBy('issuing_header.issuingh_id', 'desc')
            ->paginate($request->get('per_page', 25))
            ->withQueryString();

        $filters = $request->only(['search', 'status']);

        return view('transactions.issue', compact('issuings', 'filters'));
    }

    public function show(IssuingHeader $issue): View
    {
        $issue->load([
            'details.itemDetail.header.category',
            'details.itemDetail.header.uom',
            'details.itemDetail.uom',
            'branch',
            'customer',
            'employee',
            'creator',
        ]);

        return view('transactions.issue-show', compact('issue'));
    }

    public function create(): View
    {
        $branches = Branch::orderBy('branch_code')->get();
        $employees = Employee::orderBy('emp_code')->get();
        $customers = Customer::orderBy('cust_name')->get();
        $warehouses = Warehouse::orderBy('whsl_code')->get();

        return view('transactions.issue-create', compact('branches', 'employees', 'customers', 'warehouses'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'branch_id' => 'required|integer|exists:branch,branch_id',
            'issuingh_date' => 'required|date',
            'issuingh_type' => 'required|string|in:0,1',
            'cust_id' => 'nullable|integer|exists:customer,cust_id',
            'emp_id' => 'required|integer|exists:employee,emp_id',
            'issuingh_ba_no' => 'nullable|string|max:50',
            'issuingh_po_no' => 'nullable|string|max:50',
            'issuingh_do_no' => 'nullable|string|max:50',
            'issuingh_sent_by' => 'nullable|string|max:50',
            'issuingh_vehicle_no' => 'nullable|string|max:20',
            'issuingh_receiver_name' => 'nullable|string|max:50',
            'issuingh_notes' => 'nullable|string|max:255',
            'details' => 'nullable|array',
            'details.*.itemd_id' => 'required_with:details|integer|exists:item_detail,itemd_id',
            'details.*.issuingd_qty' => 'required_with:details|numeric|min:1',
            'details.*.issuingd_notes' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            $issue = IssuingHeader::create([
                'comp_id' => 0,
                'branch_id' => $request->branch_id,
                'issuingh_code' => $this->generateCode(),
                'issuingh_date' => $request->issuingh_date,
                'issuingh_type' => $request->issuingh_type,
                'cust_id' => $request->cust_id,
                'emp_id' => $request->emp_id,
                'issuingh_ba_no' => $request->issuingh_ba_no,
                'issuingh_po_no' => $request->issuingh_po_no,
                'issuingh_do_no' => $request->issuingh_do_no,
                'issuingh_sent_by' => $request->issuingh_sent_by,
                'issuingh_vehicle_no' => $request->issuingh_vehicle_no,
                'issuingh_receiver_name' => $request->issuingh_receiver_name,
                'issuingh_status' => '0',
                'issuingh_is_canceled' => '0',
                'issuingh_notes' => $request->issuingh_notes,
                'created_by' => auth()->id(),
                'created_time' => now(),
            ]);

            if ($request->has('details')) {
                foreach ($request->details as $detail) {
                    $issue->details()->create([
                        'itemd_id' => $detail['itemd_id'],
                        'issuingd_qty' => $detail['issuingd_qty'],
                        'issuingd_status' => '0',
                        'issuingd_is_canceled' => '0',
                        'issuingd_is_return' => '0',
                        'issuingd_notes' => $detail['issuingd_notes'] ?? '',
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('transactions.issue.show', $issue)
                ->with('success', 'Issue berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->withInput()
                ->with('error', 'Gagal menambahkan issue: '.$e->getMessage());
        }
    }

    public function edit(IssuingHeader $issue): View
    {
        $issue->load('details');
        $branches = Branch::orderBy('branch_code')->get();
        $employees = Employee::orderBy('emp_code')->get();
        $customers = Customer::orderBy('cust_name')->get();
        $warehouses = Warehouse::orderBy('whsl_code')->get();

        return view('transactions.issue-edit', compact('issue', 'branches', 'employees', 'customers', 'warehouses'));
    }

    public function update(Request $request, IssuingHeader $issue): RedirectResponse
    {
        $request->validate([
            'branch_id' => 'required|integer|exists:branch,branch_id',
            'issuingh_date' => 'required|date',
            'issuingh_type' => 'required|string|in:0,1',
            'cust_id' => 'nullable|integer|exists:customer,cust_id',
            'emp_id' => 'required|integer|exists:employee,emp_id',
            'issuingh_ba_no' => 'nullable|string|max:50',
            'issuingh_po_no' => 'nullable|string|max:50',
            'issuingh_do_no' => 'nullable|string|max:50',
            'issuingh_sent_by' => 'nullable|string|max:50',
            'issuingh_vehicle_no' => 'nullable|string|max:20',
            'issuingh_receiver_name' => 'nullable|string|max:50',
            'issuingh_notes' => 'nullable|string|max:255',
            'details' => 'nullable|array',
            'details.*.itemd_id' => 'required_with:details|integer|exists:item_detail,itemd_id',
            'details.*.issuingd_qty' => 'required_with:details|numeric|min:1',
            'details.*.issuingd_notes' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            $issue->update([
                'branch_id' => $request->branch_id,
                'issuingh_date' => $request->issuingh_date,
                'issuingh_type' => $request->issuingh_type,
                'cust_id' => $request->cust_id,
                'emp_id' => $request->emp_id,
                'issuingh_ba_no' => $request->issuingh_ba_no,
                'issuingh_po_no' => $request->issuingh_po_no,
                'issuingh_do_no' => $request->issuingh_do_no,
                'issuingh_sent_by' => $request->issuingh_sent_by,
                'issuingh_vehicle_no' => $request->issuingh_vehicle_no,
                'issuingh_receiver_name' => $request->issuingh_receiver_name,
                'issuingh_notes' => $request->issuingh_notes,
                'updated_by' => auth()->id(),
                'updated_time' => now(),
            ]);

            $issue->details()->delete();

            if ($request->has('details')) {
                foreach ($request->details as $detail) {
                    $issue->details()->create([
                        'itemd_id' => $detail['itemd_id'],
                        'issuingd_qty' => $detail['issuingd_qty'],
                        'issuingd_status' => '0',
                        'issuingd_is_canceled' => '0',
                        'issuingd_is_return' => '0',
                        'issuingd_notes' => $detail['issuingd_notes'] ?? '',
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('transactions.issue.show', $issue)
                ->with('success', 'Issue berhasil diupdate.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->withInput()
                ->with('error', 'Gagal mengupdate issue: '.$e->getMessage());
        }
    }

    public function destroy(IssuingHeader $issue): RedirectResponse
    {
        DB::beginTransaction();

        try {
            $issue->details()->delete();
            $issue->delete();
            DB::commit();

            return redirect()->route('transactions.issue')
                ->with('success', 'Issue berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Gagal menghapus issue: '.$e->getMessage());
        }
    }

    private function generateCode(): string
    {
        $date = now()->format('dmY');
        $lastIssue = IssuingHeader::where('issuingh_code', 'like', "ISHSII-{$date}%")
            ->orderBy('issuingh_code', 'desc')
            ->first();

        if ($lastIssue) {
            $lastNumber = (int) substr($lastIssue->issuingh_code, -7);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return "ISHSII-{$date}".str_pad($newNumber, 7, '0', STR_PAD_LEFT);
    }
}
