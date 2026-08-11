<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\BrokenHeader;
use App\Models\ItemDetail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BrokenController extends Controller
{
    public function index(Request $request): View
    {
        $query = BrokenHeader::query()
            ->withCount(['details as total_qty']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('broken_header.brokh_code', 'like', "%{$search}%")
                    ->orWhere('broken_header.brokh_notes', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('broken_header.brokh_status', $request->status);
        }

        if ($request->filled('canceled')) {
            $query->where('broken_header.brokh_is_canceled', $request->canceled);
        }

        $branchFilter = session('branch_filter');
        if ($branchFilter) {
            $query->where('broken_header.branch_id', $branchFilter);
        }

        $brokens = $query->orderBy('broken_header.brokh_date', 'desc')
            ->orderBy('broken_header.brokh_id', 'desc')
            ->paginate($request->get('per_page', 25))
            ->withQueryString();

        $filters = $request->only(['search', 'status', 'canceled']);

        return view('transactions.broken', compact('brokens', 'filters'));
    }

    public function show(BrokenHeader $broken): View
    {
        $broken->load([
            'details.itemDetail.header',
            'details.itemDetail.uom',
            'branch',
            'creator',
        ]);

        return view('transactions.broken-show', compact('broken'));
    }

    public function create(): View
    {
        $branches = Branch::orderBy('branch_code')->get();

        return view('transactions.broken-create', compact('branches'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'branch_id' => 'required|integer|exists:branch,branch_id',
            'brokh_date' => 'required|date',
            'brokh_status' => 'required|in:0,1,2',
            'brokh_notes' => 'nullable|string|max:255',
            'details' => 'nullable|array',
            'details.*.itemd_id' => 'required_with:details|integer|exists:item_detail,itemd_id',
            'details.*.brokd_qty' => 'required_with:details|integer|min:1',
            'details.*.brokd_notes' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            $broken = BrokenHeader::create([
                'comp_id' => 0,
                'branch_id' => $request->branch_id,
                'brokh_code' => $this->generateCode(),
                'brokh_date' => $request->brokh_date,
                'brokh_status' => $request->brokh_status,
                'brokh_is_canceled' => '0',
                'brokh_notes' => $request->brokh_notes,
                'created_by' => auth()->id(),
                'created_time' => now(),
            ]);

            if ($request->has('details')) {
                foreach ($request->details as $detail) {
                    $broken->details()->create([
                        'itemd_id' => $detail['itemd_id'],
                        'brokd_qty' => $detail['brokd_qty'],
                        'brokd_is_canceled' => '0',
                        'brokd_is_dispossed' => '0',
                        'brokd_is_wo' => '0',
                        'brokd_notes' => $detail['brokd_notes'] ?? '',
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('transactions.broken.show', $broken)
                ->with('success', 'Broken berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->withInput()
                ->with('error', 'Gagal menambahkan broken: '.$e->getMessage());
        }
    }

    public function edit(BrokenHeader $broken): View
    {
        $broken->load(['details.itemDetail.header']);
        $branches = Branch::orderBy('branch_code')->get();

        return view('transactions.broken-edit', compact('broken', 'branches'));
    }

    public function update(Request $request, BrokenHeader $broken): RedirectResponse
    {
        $request->validate([
            'branch_id' => 'required|integer|exists:branch,branch_id',
            'brokh_date' => 'required|date',
            'brokh_status' => 'required|in:0,1,2',
            'brokh_notes' => 'nullable|string|max:255',
            'details' => 'nullable|array',
            'details.*.itemd_id' => 'required_with:details|integer|exists:item_detail,itemd_id',
            'details.*.brokd_qty' => 'required_with:details|integer|min:1',
            'details.*.brokd_notes' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            $broken->update([
                'branch_id' => $request->branch_id,
                'brokh_date' => $request->brokh_date,
                'brokh_status' => $request->brokh_status,
                'brokh_notes' => $request->brokh_notes,
                'updated_by' => auth()->id(),
                'updated_time' => now(),
            ]);

            $broken->details()->delete();

            if ($request->has('details')) {
                foreach ($request->details as $detail) {
                    $broken->details()->create([
                        'itemd_id' => $detail['itemd_id'],
                        'brokd_qty' => $detail['brokd_qty'],
                        'brokd_is_canceled' => '0',
                        'brokd_is_dispossed' => '0',
                        'brokd_is_wo' => '0',
                        'brokd_notes' => $detail['brokd_notes'] ?? '',
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('transactions.broken.show', $broken)
                ->with('success', 'Broken berhasil diupdate.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->withInput()
                ->with('error', 'Gagal mengupdate broken: '.$e->getMessage());
        }
    }

    public function destroy(BrokenHeader $broken): RedirectResponse
    {
        DB::beginTransaction();

        try {
            $broken->details()->delete();
            $broken->delete();
            DB::commit();

            return redirect()->route('transactions.broken.index')
                ->with('success', 'Broken berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Gagal menghapus broken: '.$e->getMessage());
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
        $lastBroken = BrokenHeader::where('brokh_code', 'like', "BRKSII-{$date}%")
            ->orderBy('brokh_code', 'desc')
            ->first();

        if ($lastBroken) {
            $lastNumber = (int) substr($lastBroken->brokh_code, -7);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return "BRKSII-{$date}".str_pad($newNumber, 7, '0', STR_PAD_LEFT);
    }
}
