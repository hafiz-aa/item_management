<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\ChangeItemDescriptionHeader;
use App\Models\ItemDetail;
use App\Models\MasterItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ChangeDescriptionController extends Controller
{
    public function index(Request $request): View
    {
        $query = ChangeItemDescriptionHeader::query()
            ->withCount(['details as total_items']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('change_item_description_header.cidh_code', 'like', "%{$search}%")
                    ->orWhere('change_item_description_header.cidh_notes', 'like', "%{$search}%");
            });
        }

        if ($request->filled('canceled')) {
            $query->where('change_item_description_header.cidh_is_canceled', $request->canceled);
        }

        $branchFilter = session('branch_filter');
        if ($branchFilter) {
            $query->where('change_item_description_header.branch_id', $branchFilter);
        }

        $changes = $query->orderBy('change_item_description_header.cidh_date', 'desc')
            ->orderBy('change_item_description_header.cidh_id', 'desc')
            ->paginate($request->get('per_page', 25))
            ->withQueryString();

        $filters = $request->only(['search', 'canceled']);

        return view('transactions.change-description', compact('changes', 'filters'));
    }

    public function show(ChangeItemDescriptionHeader $change): View
    {
        $change->load([
            'details.itemDetail.header',
            'details.oldMaster',
            'details.newMaster',
            'branch',
            'creator',
        ]);

        return view('transactions.change-description-show', compact('change'));
    }

    public function create(): View
    {
        $branches = Branch::orderBy('branch_code')->get();
        $masterItems = MasterItem::orderBy('masti_code')->get();

        return view('transactions.change-description-create', compact('branches', 'masterItems'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'branch_id' => 'required|integer|exists:branch,branch_id',
            'cidh_date' => 'required|date',
            'cidh_notes' => 'nullable|string|max:255',
            'details' => 'nullable|array',
            'details.*.itemd_id' => 'required_with:details|integer|exists:item_detail,itemd_id',
            'details.*.masti_id_new' => 'required_with:details|integer|exists:master_item,masti_id',
            'details.*.cidd_notes' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            $change = ChangeItemDescriptionHeader::create([
                'comp_id' => 0,
                'branch_id' => $request->branch_id,
                'cidh_code' => $this->generateCode(),
                'cidh_date' => $request->cidh_date,
                'cidh_is_canceled' => '0',
                'cidh_notes' => $request->cidh_notes,
                'created_by' => auth()->id(),
                'created_time' => now(),
            ]);

            if ($request->has('details')) {
                foreach ($request->details as $detail) {
                    $change->details()->create([
                        'itemd_id' => $detail['itemd_id'],
                        'masti_id_old' => $detail['masti_id_old'] ?? ItemDetail::find($detail['itemd_id'])?->masti_id,
                        'masti_id_new' => $detail['masti_id_new'],
                        'cidd_is_canceled' => '0',
                        'cidd_notes' => $detail['cidd_notes'] ?? '',
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('transactions.change-description.show', $change)
                ->with('success', 'Change Item Description berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->withInput()
                ->with('error', 'Gagal menambahkan change item description: '.$e->getMessage());
        }
    }

    public function edit(ChangeItemDescriptionHeader $change): View
    {
        $change->load([
            'details.itemDetail.header',
            'details.oldMaster',
            'details.newMaster',
        ]);
        $branches = Branch::orderBy('branch_code')->get();
        $masterItems = MasterItem::orderBy('masti_code')->get();

        return view('transactions.change-description-edit', compact('change', 'branches', 'masterItems'));
    }

    public function update(Request $request, ChangeItemDescriptionHeader $change): RedirectResponse
    {
        $request->validate([
            'branch_id' => 'required|integer|exists:branch,branch_id',
            'cidh_date' => 'required|date',
            'cidh_notes' => 'nullable|string|max:255',
            'details' => 'nullable|array',
            'details.*.itemd_id' => 'required_with:details|integer|exists:item_detail,itemd_id',
            'details.*.masti_id_new' => 'required_with:details|integer|exists:master_item,masti_id',
            'details.*.cidd_notes' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            $change->update([
                'branch_id' => $request->branch_id,
                'cidh_date' => $request->cidh_date,
                'cidh_notes' => $request->cidh_notes,
                'updated_by' => auth()->id(),
                'updated_time' => now(),
            ]);

            $change->details()->delete();

            if ($request->has('details')) {
                foreach ($request->details as $detail) {
                    $change->details()->create([
                        'itemd_id' => $detail['itemd_id'],
                        'masti_id_old' => $detail['masti_id_old'] ?? ItemDetail::find($detail['itemd_id'])?->masti_id,
                        'masti_id_new' => $detail['masti_id_new'],
                        'cidd_is_canceled' => '0',
                        'cidd_notes' => $detail['cidd_notes'] ?? '',
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('transactions.change-description.show', $change)
                ->with('success', 'Change Item Description berhasil diupdate.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->withInput()
                ->with('error', 'Gagal mengupdate change item description: '.$e->getMessage());
        }
    }

    public function destroy(ChangeItemDescriptionHeader $change): RedirectResponse
    {
        DB::beginTransaction();

        try {
            $change->details()->delete();
            $change->delete();
            DB::commit();

            return redirect()->route('transactions.change-description.index')
                ->with('success', 'Change Item Description berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Gagal menghapus change item description: '.$e->getMessage());
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
        $lastChange = ChangeItemDescriptionHeader::where('cidh_code', 'like', "CIDSII-{$date}%")
            ->orderBy('cidh_code', 'desc')
            ->first();

        if ($lastChange) {
            $lastNumber = (int) substr($lastChange->cidh_code, -7);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return "CIDSII-{$date}".str_pad($newNumber, 7, '0', STR_PAD_LEFT);
    }
}
