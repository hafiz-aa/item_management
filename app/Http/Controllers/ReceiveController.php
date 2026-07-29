<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Employee;
use App\Models\ReceiptTransferHeader;
use App\Models\TransferHeader;
use App\Models\Warehouse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReceiveController extends Controller
{
    public function index(Request $request): View
    {
        $query = ReceiptTransferHeader::query()
            ->with(['branchFrom'])
            ->withCount(['details as total_qty']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('receipt_transfer_header.rth_code', 'like', "%{$search}%")
                    ->orWhere('receipt_transfer_header.rth_ba_no', 'like', "%{$search}%")
                    ->orWhere('receipt_transfer_header.rth_po_no', 'like', "%{$search}%")
                    ->orWhereHas('branchFrom', function ($q2) use ($search) {
                        $q2->where('branch_name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('receipt_transfer_header.rth_is_canceled', $request->status);
        }

        $branchFilter = session('branch_filter');
        if ($branchFilter) {
            $query->where('receipt_transfer_header.branch_id', $branchFilter);
        }

        $receives = $query->orderBy('receipt_transfer_header.rth_date', 'desc')
            ->orderBy('receipt_transfer_header.rth_id', 'desc')
            ->paginate($request->get('per_page', 25))
            ->withQueryString();

        $filters = $request->only(['search', 'status']);

        return view('transactions.receive', compact('receives', 'filters'));
    }

    public function show(ReceiptTransferHeader $receive): View
    {
        $receive->load([
            'details.itemDetail.header',
            'details.warehouseNew',
            'transferHeader',
            'branch',
            'branchFrom',
            'receiver',
            'creator',
        ]);

        return view('transactions.receive-show', compact('receive'));
    }

    public function create(): View
    {
        $branches = Branch::orderBy('branch_code')->get();
        $employees = Employee::orderBy('emp_code')->get();
        $warehouses = Warehouse::orderBy('whsl_code')->get();
        $transfers = TransferHeader::where('tth_status', '!=', '2')
            ->where('tth_is_canceled', '0')
            ->orderBy('tth_date', 'desc')
            ->get();

        return view('transactions.receive-create', compact('branches', 'employees', 'warehouses', 'transfers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'branch_id' => 'required|integer|exists:branch,branch_id',
            'tth_id' => 'required|integer|exists:transfer_header,tth_id',
            'rth_date' => 'required|date',
            'branch_id_from' => 'required|integer|exists:branch,branch_id',
            'emp_id_receiver' => 'required|integer|exists:employee,emp_id',
            'rth_ba_no' => 'nullable|string|max:50',
            'rth_po_no' => 'nullable|string|max:50',
            'rth_notes' => 'nullable|string|max:255',
            'details' => 'nullable|array',
            'details.*.itemd_id' => 'required_with:details|integer|exists:item_detail,itemd_id',
            'details.*.rtd_qty' => 'required_with:details|integer|min:1',
            'details.*.whsl_id_new' => 'required_with:details|integer|exists:warehouse_location,whsl_id',
            'details.*.rtd_notes' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            $receive = ReceiptTransferHeader::create([
                'comp_id' => 0,
                'branch_id' => $request->branch_id,
                'tth_id' => $request->tth_id,
                'rth_code' => $this->generateCode(),
                'rth_date' => $request->rth_date,
                'branch_id_from' => $request->branch_id_from,
                'emp_id_receiver' => $request->emp_id_receiver,
                'rth_is_canceled' => '0',
                'rth_ba_no' => $request->rth_ba_no,
                'rth_po_no' => $request->rth_po_no,
                'rth_notes' => $request->rth_notes,
                'created_by' => auth()->id(),
                'created_time' => now(),
            ]);

            if ($request->has('details')) {
                foreach ($request->details as $detail) {
                    $receive->details()->create([
                        'ttd_id' => $detail['ttd_id'] ?? 0,
                        'itemd_id' => $detail['itemd_id'],
                        'rtd_qty' => $detail['rtd_qty'],
                        'whsl_id_new' => $detail['whsl_id_new'],
                        'rtd_is_canceled' => '0',
                        'rtd_notes' => $detail['rtd_notes'] ?? '',
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('transactions.receive.show', $receive)
                ->with('success', 'Receive berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->withInput()
                ->with('error', 'Gagal menambahkan receive: '.$e->getMessage());
        }
    }

    public function edit(ReceiptTransferHeader $receive): View
    {
        $receive->load('details');
        $branches = Branch::orderBy('branch_code')->get();
        $employees = Employee::orderBy('emp_code')->get();
        $warehouses = Warehouse::orderBy('whsl_code')->get();
        $transfers = TransferHeader::orderBy('tth_date', 'desc')->get();

        return view('transactions.receive-edit', compact('receive', 'branches', 'employees', 'warehouses', 'transfers'));
    }

    public function update(Request $request, ReceiptTransferHeader $receive): RedirectResponse
    {
        $request->validate([
            'branch_id' => 'required|integer|exists:branch,branch_id',
            'tth_id' => 'required|integer|exists:transfer_header,tth_id',
            'rth_date' => 'required|date',
            'branch_id_from' => 'required|integer|exists:branch,branch_id',
            'emp_id_receiver' => 'required|integer|exists:employee,emp_id',
            'rth_ba_no' => 'nullable|string|max:50',
            'rth_po_no' => 'nullable|string|max:50',
            'rth_notes' => 'nullable|string|max:255',
            'details' => 'nullable|array',
            'details.*.itemd_id' => 'required_with:details|integer|exists:item_detail,itemd_id',
            'details.*.rtd_qty' => 'required_with:details|integer|min:1',
            'details.*.whsl_id_new' => 'required_with:details|integer|exists:warehouse_location,whsl_id',
            'details.*.rtd_notes' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            $receive->update([
                'branch_id' => $request->branch_id,
                'tth_id' => $request->tth_id,
                'rth_date' => $request->rth_date,
                'branch_id_from' => $request->branch_id_from,
                'emp_id_receiver' => $request->emp_id_receiver,
                'rth_ba_no' => $request->rth_ba_no,
                'rth_po_no' => $request->rth_po_no,
                'rth_notes' => $request->rth_notes,
                'updated_by' => auth()->id(),
                'updated_time' => now(),
            ]);

            $receive->details()->delete();

            if ($request->has('details')) {
                foreach ($request->details as $detail) {
                    $receive->details()->create([
                        'ttd_id' => $detail['ttd_id'] ?? 0,
                        'itemd_id' => $detail['itemd_id'],
                        'rtd_qty' => $detail['rtd_qty'],
                        'whsl_id_new' => $detail['whsl_id_new'],
                        'rtd_is_canceled' => '0',
                        'rtd_notes' => $detail['rtd_notes'] ?? '',
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('transactions.receive.show', $receive)
                ->with('success', 'Receive berhasil diupdate.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->withInput()
                ->with('error', 'Gagal mengupdate receive: '.$e->getMessage());
        }
    }

    public function destroy(ReceiptTransferHeader $receive): RedirectResponse
    {
        DB::beginTransaction();

        try {
            $receive->details()->delete();
            $receive->delete();
            DB::commit();

            return redirect()->route('transactions.receive')
                ->with('success', 'Receive berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Gagal menghapus receive: '.$e->getMessage());
        }
    }

    private function generateCode(): string
    {
        $date = now()->format('dmY');
        $lastReceive = ReceiptTransferHeader::where('rth_code', 'like', "RECSII-{$date}%")
            ->orderBy('rth_code', 'desc')
            ->first();

        if ($lastReceive) {
            $lastNumber = (int) substr($lastReceive->rth_code, -7);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return "RECSII-{$date}".str_pad($newNumber, 7, '0', STR_PAD_LEFT);
    }
}
