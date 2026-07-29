@extends('layouts.app')

@section('title', 'Detail Receive')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0">Receive: {{ $receive->rth_code }}</h5>
        <div class="d-flex gap-2">
            <a href="{{ route('transactions.receive.edit', $receive) }}" class="btn btn-sm btn-warning text-white"><i class="bi bi-pencil me-1"></i> Edit</a>
            <a href="{{ route('transactions.receive.index') }}" class="btn btn-sm btn-secondary"><i class="bi bi-arrow-left me-1"></i> Back</a>
        </div>
    </div>
    <div class="card-body">
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <label class="form-label fw-bold">Code</label>
                <div>{{ $receive->rth_code }}</div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Date</label>
                <div>{{ $receive->rth_date?->format('d/m/Y') ?? '-' }}</div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Reference No (Transfer)</label>
                <div>{{ $receive->transferHeader->tth_code ?? '-' }}</div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Branch Tujuan</label>
                <div>{{ $receive->branch->branch_name ?? '-' }}</div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Branch Asal</label>
                <div>{{ $receive->branchFrom->branch_name ?? '-' }}</div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Penerima</label>
                <div>{{ $receive->receiver->emp_name ?? '-' }}</div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">BA No</label>
                <div>{{ $receive->rth_ba_no ?? '-' }}</div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Purchase No</label>
                <div>{{ $receive->rth_po_no ?? '-' }}</div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Canceled</label>
                <div>
                    @if($receive->rth_is_canceled === '1')
                        <span class="badge bg-danger">Ya</span>
                    @else
                        <span class="badge bg-success">Tidak</span>
                    @endif
                </div>
            </div>
            <div class="col-md-9">
                <label class="form-label fw-bold">Notes</label>
                <div>{{ $receive->rth_notes ?? '-' }}</div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Created By</label>
                <div>{{ $receive->creator->users_names ?? '-' }}</div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Created Date</label>
                <div>{{ $receive->created_time?->format('d/m/Y H:i') ?? '-' }}</div>
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
                        <th>Qty</th>
                        <th>Warehouse Tujuan</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($receive->details as $detail)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $detail->itemDetail->itemd_code ?? '-' }}</td>
                            <td>{{ $detail->itemDetail->header->masti_name ?? '-' }}</td>
                            <td>{{ $detail->rtd_qty }}</td>
                            <td>{{ $detail->warehouseNew->whsl_name ?? '-' }}</td>
                            <td>{{ $detail->rtd_notes ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Tidak ada detail item.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
