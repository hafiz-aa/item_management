<?php

namespace App\Http\Controllers;

use App\Models\BrokenDetail;
use App\Models\Customer;
use App\Models\DisposalDetail;
use App\Models\IssuingDetail;
use App\Models\ItemDetail;
use App\Models\ReturnDetail;
use App\Models\TransferDetail;
use App\Models\Warehouse;
use App\Models\WriteOffDetail;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function transfer(Request $request): View
    {
        $query = TransferDetail::query()
            ->join('transfer_header', 'transfer_detail.tth_id', '=', 'transfer_header.tth_id')
            ->join('item_detail', 'transfer_detail.itemd_id', '=', 'item_detail.itemd_id')
            ->leftJoin('master_item', 'item_detail.masti_id', '=', 'master_item.masti_id')
            ->leftJoin('warehouse_location as wf', 'transfer_detail.whsl_id_from', '=', 'wf.whsl_id')
            ->leftJoin('branch as bf', 'transfer_header.branch_id', '=', 'bf.branch_id')
            ->leftJoin('branch as bt', 'transfer_header.branch_id_to', '=', 'bt.branch_id')
            ->select(
                'transfer_header.tth_code',
                'transfer_header.tth_date',
                'transfer_header.tth_status',
                'transfer_header.tth_is_canceled',
                'transfer_header.tth_notes',
                'item_detail.itemd_code',
                'item_detail.itemd_serial_no',
                'master_item.masti_name',
                'wf.whsl_name as wh_from',
                'bf.branch_name as branch_from',
                'bt.branch_name as branch_to',
            );

        $this->applyDateRange($query, 'transfer_header.tth_date', $request);
        $this->applyBranchFilter($query, 'transfer_header.branch_id');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('transfer_header.tth_code', 'like', "%{$request->search}%")
                    ->orWhere('item_detail.itemd_code', 'like', "%{$request->search}%")
                    ->orWhere('master_item.masti_name', 'like', "%{$request->search}%");
            });
        }

        $rows = $query->orderBy('transfer_header.tth_date', 'desc')
            ->orderBy('transfer_header.tth_id', 'desc')
            ->paginate($request->get('per_page', 25))
            ->withQueryString();

        $filters = $this->filters($request, ['search']);

        return view('reports.transfer', compact('rows', 'filters'));
    }

    public function issuing(Request $request): View
    {
        $query = IssuingDetail::query()
            ->join('issuing_header', 'issuing_detail.issuingh_id', '=', 'issuing_header.issuingh_id')
            ->join('item_detail', 'issuing_detail.itemd_id', '=', 'item_detail.itemd_id')
            ->leftJoin('master_item', 'item_detail.masti_id', '=', 'master_item.masti_id')
            ->leftJoin('customer', 'issuing_header.cust_id', '=', 'customer.cust_id')
            ->select(
                'issuing_header.issuingh_code',
                'issuing_header.issuingh_date',
                'issuing_header.issuingh_type',
                'issuing_header.issuingh_status',
                'issuing_header.issuingh_is_canceled',
                'customer.cust_name',
                'item_detail.itemd_code',
                'item_detail.itemd_serial_no',
                'master_item.masti_name',
                'issuing_detail.issuingd_qty',
                'issuing_detail.issuingd_notes',
            );

        $this->applyDateRange($query, 'issuing_header.issuingh_date', $request);
        $this->applyBranchFilter($query, 'issuing_header.branch_id');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('issuing_header.issuingh_code', 'like', "%{$request->search}%")
                    ->orWhere('customer.cust_name', 'like', "%{$request->search}%")
                    ->orWhere('item_detail.itemd_code', 'like', "%{$request->search}%")
                    ->orWhere('master_item.masti_name', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('issuing_header.issuingh_type', $request->type);
        }

        $rows = $query->orderBy('issuing_header.issuingh_date', 'desc')
            ->orderBy('issuing_header.issuingh_id', 'desc')
            ->paginate($request->get('per_page', 25))
            ->withQueryString();

        $filters = $this->filters($request, ['search', 'type']);

        return view('reports.issuing', compact('rows', 'filters'));
    }

    public function returning(Request $request): View
    {
        $query = ReturnDetail::query()
            ->join('return_header', 'return_detail.reth_id', '=', 'return_header.reth_id')
            ->join('issuing_detail', 'return_detail.issuingd_id', '=', 'issuing_detail.issuingd_id')
            ->join('issuing_header', 'return_header.issuingh_id', '=', 'issuing_header.issuingh_id')
            ->join('item_detail', 'issuing_detail.itemd_id', '=', 'item_detail.itemd_id')
            ->leftJoin('master_item', 'item_detail.masti_id', '=', 'master_item.masti_id')
            ->leftJoin('warehouse_location', 'return_detail.whsl_id', '=', 'warehouse_location.whsl_id')
            ->select(
                'return_header.reth_code',
                'return_header.reth_date',
                'return_header.reth_is_canceled',
                'issuing_header.issuingh_code as ref_issue_code',
                'item_detail.itemd_code',
                'item_detail.itemd_serial_no',
                'master_item.masti_name',
                'return_detail.retd_qty',
                'warehouse_location.whsl_name',
                'return_detail.retd_notes',
            );

        $this->applyDateRange($query, 'return_header.reth_date', $request);
        $this->applyBranchFilter($query, 'return_header.branch_id');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('return_header.reth_code', 'like', "%{$request->search}%")
                    ->orWhere('issuing_header.issuingh_code', 'like', "%{$request->search}%")
                    ->orWhere('item_detail.itemd_code', 'like', "%{$request->search}%")
                    ->orWhere('master_item.masti_name', 'like', "%{$request->search}%");
            });
        }

        $rows = $query->orderBy('return_header.reth_date', 'desc')
            ->orderBy('return_header.reth_id', 'desc')
            ->paginate($request->get('per_page', 25))
            ->withQueryString();

        $filters = $this->filters($request, ['search']);

        return view('reports.returning', compact('rows', 'filters'));
    }

    public function broken(Request $request): View
    {
        $query = BrokenDetail::query()
            ->join('broken_header', 'broken_detail.brokh_id', '=', 'broken_header.brokh_id')
            ->join('item_detail', 'broken_detail.itemd_id', '=', 'item_detail.itemd_id')
            ->leftJoin('master_item', 'item_detail.masti_id', '=', 'master_item.masti_id')
            ->select(
                'broken_header.brokh_code',
                'broken_header.brokh_date',
                'broken_header.brokh_status',
                'broken_header.brokh_is_canceled',
                'broken_header.brokh_notes',
                'item_detail.itemd_code',
                'item_detail.itemd_serial_no',
                'master_item.masti_name',
                'broken_detail.brokd_qty',
                'broken_detail.brokd_is_wo',
                'broken_detail.brokd_is_dispossed',
                'broken_detail.brokd_notes',
            );

        $this->applyDateRange($query, 'broken_header.brokh_date', $request);
        $this->applyBranchFilter($query, 'broken_header.branch_id');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('broken_header.brokh_code', 'like', "%{$request->search}%")
                    ->orWhere('item_detail.itemd_code', 'like', "%{$request->search}%")
                    ->orWhere('master_item.masti_name', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('broken_header.brokh_status', $request->status);
        }

        $rows = $query->orderBy('broken_header.brokh_date', 'desc')
            ->orderBy('broken_header.brokh_id', 'desc')
            ->paginate($request->get('per_page', 25))
            ->withQueryString();

        $filters = $this->filters($request, ['search', 'status']);

        return view('reports.broken', compact('rows', 'filters'));
    }

    public function writeOff(Request $request): View
    {
        $query = WriteOffDetail::query()
            ->join('write_off_header', 'write_off_detail.woh_id', '=', 'write_off_header.woh_id')
            ->join('item_detail', 'write_off_detail.itemd_id', '=', 'item_detail.itemd_id')
            ->leftJoin('master_item', 'item_detail.masti_id', '=', 'master_item.masti_id')
            ->select(
                'write_off_header.woh_code',
                'write_off_header.woh_date',
                'write_off_header.woh_sources',
                'write_off_header.woh_is_canceled',
                'write_off_header.woh_notes',
                'item_detail.itemd_code',
                'item_detail.itemd_serial_no',
                'master_item.masti_name',
                'write_off_detail.wod_qty',
                'write_off_detail.wod_notes',
            );

        $this->applyDateRange($query, 'write_off_header.woh_date', $request);
        $this->applyBranchFilter($query, 'write_off_header.branch_id');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('write_off_header.woh_code', 'like', "%{$request->search}%")
                    ->orWhere('item_detail.itemd_code', 'like', "%{$request->search}%")
                    ->orWhere('master_item.masti_name', 'like', "%{$request->search}%");
            });
        }

        $rows = $query->orderBy('write_off_header.woh_date', 'desc')
            ->orderBy('write_off_header.woh_id', 'desc')
            ->paginate($request->get('per_page', 25))
            ->withQueryString();

        $filters = $this->filters($request, ['search']);

        return view('reports.write-off', compact('rows', 'filters'));
    }

    public function disposal(Request $request): View
    {
        $query = DisposalDetail::query()
            ->join('dispossal_header', 'dispossal_detail.disph_id', '=', 'dispossal_header.disph_id')
            ->join('item_detail', 'dispossal_detail.itemd_id', '=', 'item_detail.itemd_id')
            ->leftJoin('master_item', 'item_detail.masti_id', '=', 'master_item.masti_id')
            ->leftJoin('customer', 'dispossal_header.cust_id', '=', 'customer.cust_id')
            ->select(
                'dispossal_header.disph_code',
                'dispossal_header.disph_date',
                'dispossal_header.disph_sources',
                'dispossal_header.disph_is_canceled',
                'dispossal_header.disph_notes',
                'customer.cust_name',
                'item_detail.itemd_code',
                'item_detail.itemd_serial_no',
                'master_item.masti_name',
                'dispossal_detail.dispd_qty',
                'dispossal_detail.dispd_notes',
            );

        $this->applyDateRange($query, 'dispossal_header.disph_date', $request);
        $this->applyBranchFilter($query, 'dispossal_header.branch_id');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('dispossal_header.disph_code', 'like', "%{$request->search}%")
                    ->orWhere('customer.cust_name', 'like', "%{$request->search}%")
                    ->orWhere('item_detail.itemd_code', 'like', "%{$request->search}%")
                    ->orWhere('master_item.masti_name', 'like', "%{$request->search}%");
            });
        }

        $rows = $query->orderBy('dispossal_header.disph_date', 'desc')
            ->orderBy('dispossal_header.disph_id', 'desc')
            ->paginate($request->get('per_page', 25))
            ->withQueryString();

        $filters = $this->filters($request, ['search']);

        return view('reports.disposal', compact('rows', 'filters'));
    }

    public function position(Request $request): View
    {
        $query = ItemDetail::query()
            ->join('master_item', 'item_detail.masti_id', '=', 'master_item.masti_id')
            ->leftJoin('warehouse_location', 'item_detail.whsl_id', '=', 'warehouse_location.whsl_id')
            ->leftJoin('branch', 'item_detail.branch_id', '=', 'branch.branch_id')
            ->leftJoin('uom', 'item_detail.uom_id', '=', 'uom.uom_id')
            ->select(
                'item_detail.itemd_code',
                'item_detail.itemd_serial_no',
                'item_detail.itemd_capacity',
                'item_detail.itemd_qty',
                'master_item.masti_name',
                'warehouse_location.whsl_name',
                'branch.branch_name',
                'uom.uom_name',
            );

        $this->applyBranchFilter($query, 'item_detail.branch_id');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('item_detail.itemd_code', 'like', "%{$request->search}%")
                    ->orWhere('item_detail.itemd_serial_no', 'like', "%{$request->search}%")
                    ->orWhere('master_item.masti_name', 'like', "%{$request->search}%")
                    ->orWhere('master_item.masti_code', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('whsl_id')) {
            $query->where('item_detail.whsl_id', $request->whsl_id);
        }

        $rows = $query->orderBy('item_detail.itemd_code')
            ->paginate($request->get('per_page', 25))
            ->withQueryString();

        $filters = $this->filters($request, ['search', 'whsl_id']);

        $warehouses = Warehouse::orderBy('whsl_code')->get();

        return view('reports.position', compact('rows', 'filters', 'warehouses'));
    }

    public function aging(Request $request): View
    {
        $query = ItemDetail::query()
            ->join('master_item', 'item_detail.masti_id', '=', 'master_item.masti_id')
            ->leftJoin('warehouse_location', 'item_detail.whsl_id', '=', 'warehouse_location.whsl_id')
            ->leftJoin('branch', 'item_detail.branch_id', '=', 'branch.branch_id')
            ->select(
                'item_detail.itemd_code',
                'item_detail.itemd_serial_no',
                'item_detail.itemd_acquired_date',
                'item_detail.itemd_qty',
                'master_item.masti_name',
                'warehouse_location.whsl_name',
                'branch.branch_name',
            );

        $this->applyBranchFilter($query, 'item_detail.branch_id');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('item_detail.itemd_code', 'like', "%{$request->search}%")
                    ->orWhere('item_detail.itemd_serial_no', 'like', "%{$request->search}%")
                    ->orWhere('master_item.masti_name', 'like', "%{$request->search}%")
                    ->orWhere('master_item.masti_code', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('aging')) {
            $this->applyAgingBucket($query, 'item_detail.itemd_acquired_date', $request->aging);
        }

        $rows = $query->orderBy('item_detail.itemd_acquired_date')
            ->paginate($request->get('per_page', 25))
            ->withQueryString();

        $filters = $this->filters($request, ['search', 'aging']);

        return view('reports.aging', compact('rows', 'filters'));
    }

    public function vendor(Request $request): View
    {
        $query = Customer::query()
            ->where('customer.cust_type', '1')
            ->withCount(['items as total_items'])
            ->withSum(['items as total_qty'], 'itemd_qty');

        $this->applyBranchFilter($query, 'customer.branch_id');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('customer.cust_code', 'like', "%{$request->search}%")
                    ->orWhere('customer.cust_name', 'like', "%{$request->search}%")
                    ->orWhere('customer.cust_email', 'like', "%{$request->search}%")
                    ->orWhere('customer.cust_phone', 'like', "%{$request->search}%");
            });
        }

        $rows = $query->orderBy('customer.cust_code')
            ->paginate($request->get('per_page', 25))
            ->withQueryString();

        $filters = $this->filters($request, ['search']);

        return view('reports.vendor', compact('rows', 'filters'));
    }

    private function filters(Request $request, array $keys): array
    {
        return $request->only(array_merge(['date_from', 'date_to'], $keys));
    }

    private function applyDateRange($query, string $column, Request $request): void
    {
        if ($request->filled('date_from')) {
            $query->where($column, '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where($column, '<=', $request->date_to);
        }
    }

    private function applyBranchFilter($query, string $column): void
    {
        $branchFilter = session('branch_filter');
        if ($branchFilter) {
            $query->where($column, $branchFilter);
        }
    }

    private function applyAgingBucket($query, string $column, string $bucket): void
    {
        $today = now()->startOfDay();

        match ($bucket) {
            '1' => $query->whereBetween($column, [$today->copy()->subDays(30), $today]),
            '2' => $query->whereBetween($column, [$today->copy()->subDays(60), $today->copy()->subDays(31)]),
            '3' => $query->whereBetween($column, [$today->copy()->subDays(90), $today->copy()->subDays(61)]),
            '4' => $query->whereBetween($column, [$today->copy()->subDays(180), $today->copy()->subDays(91)]),
            '5' => $query->where($column, '<', $today->copy()->subDays(180)),
            default => null,
        };
    }
}
