@extends('layouts.app')

@section('title', 'Detail Return')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0">Return: {{ $return->reth_code }}</h5>
        <div class="d-flex gap-2">
            <a href="{{ route('transactions.return.edit', $return) }}" class="btn btn-sm btn-warning text-white"><i class="bi bi-pencil me-1"></i> Edit</a>
            <a href="{{ route('transactions.return.index') }}" class="btn btn-sm btn-secondary"><i class="bi bi-arrow-left me-1"></i> Back</a>
        </div>
    </div>
    <div class="card-body">
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <label class="form-label fw-bold">Code</label>
                <div>{{ $return->reth_code }}</div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Date</label>
                <div>{{ $return->reth_date?->format('d/m/Y') ?? '-' }}</div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Ref Issue No</label>
                <div>{{ $return->issuingHeader->issuingh_code ?? '-' }}</div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Ref No</label>
                <div>{{ $return->reth_ref_no ?? '-' }}</div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Branch</label>
                <div>{{ $return->branch->branch_name ?? '-' }}</div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Customer / Vendor</label>
                <div>{{ $return->issuingHeader->customer->cust_name ?? '-' }}</div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Penerima</label>
                <div>{{ $return->receiver->emp_name ?? '-' }}</div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">BA No</label>
                <div>{{ $return->reth_ba_no ?? '-' }}</div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Purchase No</label>
                <div>{{ $return->reth_po_no ?? '-' }}</div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Warehouse</label>
                <div>{{ $return->warehouse->whsl_name ?? '-' }}</div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Canceled</label>
                <div>
                    @if($return->reth_is_canceled === '1')
                        <span class="badge bg-danger">Ya</span>
                    @else
                        <span class="badge bg-success">Tidak</span>
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold">Notes</label>
                <div>{{ $return->reth_notes ?? '-' }}</div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Created By</label>
                <div>{{ $return->creator->users_names ?? '-' }}</div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Created Date</label>
                <div>{{ $return->created_time?->format('d/m/Y H:i') ?? '-' }}</div>
            </div>
        </div>

        <h6 class="fw-bold mb-2">Detail Items</h6>
        <div class="table-responsive">
            <table class="table table-hover table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Item Code</th>
                        <th>Item Name</th>
                        <th>Serial No</th>
                        <th>Capacity</th>
                        <th>UoM</th>
                        <th>Category</th>
                        <th>Qty</th>
                        <th>Warehouse</th>
                        <th>Canceled</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($return->details as $detail)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $detail->issuingDetail->itemDetail->itemd_code ?? '-' }}</td>
                            <td>{{ $detail->issuingDetail->itemDetail->header->masti_name ?? '-' }}</td>
                            <td>{{ $detail->issuingDetail->itemDetail->itemd_serial_no ?? '-' }}</td>
                            <td>{{ $detail->issuingDetail->itemDetail->itemd_capacity ?? '-' }}</td>
                            <td>{{ $detail->issuingDetail->itemDetail->uom->uom_name ?? $detail->issuingDetail->itemDetail->header->uom->uom_name ?? '-' }}</td>
                            <td>{{ $detail->issuingDetail->itemDetail->header->category->cati_name ?? '-' }}</td>
                            <td>{{ $detail->retd_qty }}</td>
                            <td>{{ $detail->warehouseNew->whsl_name ?? '-' }}</td>
                            <td>
                                @if($detail->retd_is_canceled === '1')
                                    <span class="badge bg-danger">Ya</span>
                                @else
                                    <span class="badge bg-success">Tidak</span>
                                @endif
                            </td>
                            <td>{{ $detail->retd_notes ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-center py-4 text-muted">Tidak ada detail item.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
