@extends('layouts.app')

@section('title', 'Detail Disposal')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0">Disposal: {{ $disposal->disph_code }}</h5>
        <div class="d-flex gap-2">
            <a href="{{ route('transactions.disposal.edit', $disposal) }}" class="btn btn-sm btn-warning text-white"><i class="bi bi-pencil me-1"></i> Edit</a>
            <a href="{{ route('transactions.disposal.index') }}" class="btn btn-sm btn-secondary"><i class="bi bi-arrow-left me-1"></i> Back</a>
        </div>
    </div>
    <div class="card-body">
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <label class="form-label fw-bold">Code</label>
                <div>{{ $disposal->disph_code }}</div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Date</label>
                <div>{{ $disposal->disph_date?->format('d/m/Y') ?? '-' }}</div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Branch</label>
                <div>{{ $disposal->branch->branch_name ?? '-' }}</div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Transaction Source</label>
                <div>
                    @if($disposal->disph_sources === '1')
                        <span class="badge bg-info text-dark">Change Description</span>
                    @else
                        <span class="badge bg-warning text-dark">Broken</span>
                    @endif
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Ref Broken No</label>
                <div>{{ $disposal->brokenHeader->brokh_code ?? '-' }}</div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Customer</label>
                <div>{{ $disposal->customer->cust_name ?? '-' }}</div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Disposed By</label>
                <div>{{ $disposal->disposedBy->emp_name ?? '-' }}</div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Canceled</label>
                <div>
                    @if($disposal->disph_is_canceled === '1')
                        <span class="badge bg-danger">Ya</span>
                    @else
                        <span class="badge bg-success">Tidak</span>
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold">Reason</label>
                <div>{{ $disposal->disph_reason ?? '-' }}</div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Created By</label>
                <div>{{ $disposal->creator->users_names ?? '-' }}</div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Created Date</label>
                <div>{{ $disposal->created_time?->format('d/m/Y H:i') ?? '-' }}</div>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold">Notes</label>
                <div>{{ $disposal->disph_notes ?? '-' }}</div>
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
                        <th>Qty</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($disposal->details as $detail)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $detail->itemDetail->itemd_code ?? '-' }}</td>
                            <td>{{ $detail->itemDetail->header->masti_name ?? '-' }}</td>
                            <td>{{ $detail->itemDetail->itemd_serial_no ?? '-' }}</td>
                            <td>{{ $detail->itemDetail->itemd_capacity ?? '-' }}</td>
                            <td>{{ $detail->itemDetail->uom->uom_name ?? '-' }}</td>
                            <td>{{ $detail->dispd_qty }}</td>
                            <td>{{ $detail->dispd_notes ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">Tidak ada detail item.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
