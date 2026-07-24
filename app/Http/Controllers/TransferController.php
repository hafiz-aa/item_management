<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransferRequest;
use App\Http\Requests\UpdateTransferRequest;
use App\Models\Branch;
use App\Models\Employee;
use App\Models\TransferHeader;
use App\Models\Warehouse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TransferController extends Controller
{
    public function index(Request $request): View
    {
        $query = TransferHeader::query()
            ->with(['branch', 'branchTo']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('transfer_header.tth_code', 'like', "%{$search}%")
                    ->orWhereHas('branch', function ($q2) use ($search) {
                        $q2->where('branch_name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('branchTo', function ($q2) use ($search) {
                        $q2->where('branch_name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('transfer_header.tth_status', $request->status);
        }

        if ($request->filled('branch_id')) {
            $query->where('transfer_header.branch_id', $request->branch_id);
        }

        $transfers = $query->orderBy('transfer_header.tth_date', 'desc')
            ->orderBy('transfer_header.tth_id', 'desc')
            ->paginate($request->get('per_page', 25))
            ->withQueryString();

        $branches = Branch::orderBy('branch_code')->get();
        $filters = $request->only(['search', 'status', 'branch_id']);

        return view('transfers.index', compact('transfers', 'branches', 'filters'));
    }

    public function create(): View
    {
        $branches = Branch::orderBy('branch_code')->get();
        $employees = Employee::orderBy('emp_code')->get();
        $warehouses = Warehouse::orderBy('whsl_code')->get();

        return view('transfers.create', compact('branches', 'employees', 'warehouses'));
    }

    public function store(StoreTransferRequest $request): RedirectResponse
    {
        DB::beginTransaction();

        try {
            $transfer = TransferHeader::create([
                'branch_id' => $request->branch_id,
                'tth_code' => $this->generateCode(),
                'tth_date' => $request->tth_date,
                'branch_id_to' => $request->branch_id_to,
                'tth_status' => $request->tth_status,
                'emp_id_sender' => $request->emp_id_sender,
                'tth_notes' => $request->tth_notes,
                'created_by' => auth()->id(),
                'created_time' => now(),
            ]);

            if ($request->has('details')) {
                foreach ($request->details as $detail) {
                    $transfer->details()->create([
                        'itemd_id' => $detail['itemd_id'],
                        'whsl_id_from' => $detail['whsl_id_from'],
                        'ttd_status' => $detail['ttd_status'] ?? '0',
                        'ttd_notes' => $detail['ttd_notes'] ?? '',
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('transfers.index')
                ->with('success', 'Transfer berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->withInput()
                ->with('error', 'Gagal menambahkan transfer: '.$e->getMessage());
        }
    }

    public function show(TransferHeader $transfer): View
    {
        $transfer->load(['details.itemDetail', 'details.warehouse', 'branch', 'branchTo', 'employee']);

        return view('transfers.show', compact('transfer'));
    }

    public function edit(TransferHeader $transfer): View
    {
        $transfer->load('details');
        $branches = Branch::orderBy('branch_code')->get();
        $employees = Employee::orderBy('emp_code')->get();
        $warehouses = Warehouse::orderBy('whsl_code')->get();

        return view('transfers.edit', compact('transfer', 'branches', 'employees', 'warehouses'));
    }

    public function update(UpdateTransferRequest $request, TransferHeader $transfer): RedirectResponse
    {
        DB::beginTransaction();

        try {
            $transfer->update([
                'branch_id' => $request->branch_id,
                'tth_date' => $request->tth_date,
                'branch_id_to' => $request->branch_id_to,
                'tth_status' => $request->tth_status,
                'emp_id_sender' => $request->emp_id_sender,
                'tth_notes' => $request->tth_notes,
                'updated_by' => auth()->id(),
                'updated_time' => now(),
            ]);

            $transfer->details()->delete();

            if ($request->has('details')) {
                foreach ($request->details as $detail) {
                    $transfer->details()->create([
                        'itemd_id' => $detail['itemd_id'],
                        'whsl_id_from' => $detail['whsl_id_from'],
                        'ttd_status' => $detail['ttd_status'] ?? '0',
                        'ttd_notes' => $detail['ttd_notes'] ?? '',
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('transfers.index')
                ->with('success', 'Transfer berhasil diupdate.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->withInput()
                ->with('error', 'Gagal mengupdate transfer: '.$e->getMessage());
        }
    }

    public function destroy(TransferHeader $transfer): RedirectResponse
    {
        DB::beginTransaction();

        try {
            $transfer->details()->delete();
            $transfer->delete();
            DB::commit();

            return redirect()->route('transfers.index')
                ->with('success', 'Transfer berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Gagal menghapus transfer: '.$e->getMessage());
        }
    }

    private function generateCode(): string
    {
        $date = now()->format('dmY');
        $lastTransfer = TransferHeader::where('tth_code', 'like', "TRANSSII-{$date}%")
            ->orderBy('tth_code', 'desc')
            ->first();

        if ($lastTransfer) {
            $lastNumber = (int) substr($lastTransfer->tth_code, -7);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return "TRANSSII-{$date}".str_pad($newNumber, 7, '0', STR_PAD_LEFT);
    }
}
