@extends('layouts.app')

@section('title', 'Detail Broken')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0">Broken: {{ $broken->brokh_code }}</h5>
        <div class="d-flex gap-2">
            <a href="{{ route('transactions.broken.edit', $broken) }}" class="btn btn-sm btn-warning text-white"><i class="bi bi-pencil me-1"></i> Edit</a>
            <a href="{{ route('transactions.broken.index') }}" class="btn btn-sm btn-secondary"><i class="bi bi-arrow-left me-1"></i> Back</a>
        </div>
    </div>
    <div class="card-body">
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <label class="form-label fw-bold">Code</label>
                <div>{{ $broken->brokh_code }}</div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Date</label>
                <div>{{ $broken->brokh_date?->format('d/m/Y') ?? '-' }}</div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Branch</label>
                <div>{{ $broken->branch->branch_name ?? '-' }}</div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Status</label>
                <div>
                    @if($broken->brokh_status === '2')
                        <span class="badge bg-success">Selesai</span>
                    @elseif($broken->brokh_status === '1')
                        <span class="badge bg-warning text-dark">Proses</span>
                    @else
                        <span class="badge bg-secondary">Belum</span>
                    @endif
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Canceled</label>
                <div>
                    @if($broken->brokh_is_canceled === '1')
                        <span class="badge bg-danger">Ya</span>
                    @else
                        <span class="badge bg-success">Tidak</span>
                    @endif
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Canceled Reason</label>
                <div>{{ $broken->brokh_canceled_reason ?? '-' }}</div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Created By</label>
                <div>{{ $broken->creator->users_names ?? '-' }}</div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Created Date</label>
                <div>{{ $broken->created_time?->format('d/m/Y H:i') ?? '-' }}</div>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold">Notes</label>
                <div>{{ $broken->brokh_notes ?? '-' }}</div>
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
                        <th>Disposed</th>
                        <th>Write-off</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($broken->details as $detail)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $detail->itemDetail->itemd_code ?? '-' }}</td>
                            <td>{{ $detail->itemDetail->header->masti_name ?? '-' }}</td>
                            <td>{{ $detail->itemDetail->itemd_serial_no ?? '-' }}</td>
                            <td>{{ $detail->itemDetail->itemd_capacity ?? '-' }}</td>
                            <td>{{ $detail->itemDetail->uom->uom_name ?? '-' }}</td>
                            <td>{{ $detail->brokd_qty }}</td>
                            <td>
                                @if($detail->brokd_is_dispossed === '1')
                                    <span class="badge bg-danger">Ya</span>
                                @else
                                    <span class="badge bg-success">Tidak</span>
                                @endif
                            </td>
                            <td>
                                @if($detail->brokd_is_wo === '1')
                                    <span class="badge bg-danger">Ya</span>
                                @else
                                    <span class="badge bg-success">Tidak</span>
                                @endif
                            </td>
                            <td>{{ $detail->brokd_notes ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-4 text-muted">Tidak ada detail item.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
