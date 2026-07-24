@extends('layouts.app')

@section('title', 'Detail Transfer')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0">Transfer: {{ $transfer->tth_code }}</h5>
        <div class="d-flex gap-2">
            <a href="{{ route('transfers.edit', $transfer) }}" class="btn btn-sm btn-warning text-white"><i class="bi bi-pencil me-1"></i> Edit</a>
            <a href="{{ route('transfers.index') }}" class="btn btn-sm btn-secondary"><i class="bi bi-arrow-left me-1"></i> Back</a>
        </div>
    </div>
    <div class="card-body">
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <label class="form-label fw-bold">Code</label>
                <div>{{ $transfer->tth_code }}</div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Date</label>
                <div>{{ $transfer->tth_date?->format('d/m/Y') ?? '-' }}</div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Branch Asal</label>
                <div>{{ $transfer->branch->branch_name ?? '-' }}</div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Branch Tujuan</label>
                <div>{{ $transfer->branchTo->branch_name ?? '-' }}</div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Pengirim</label>
                <div>{{ $transfer->employee->emp_name ?? '-' }}</div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Status</label>
                <div>
                    @if($transfer->tth_status === '0')
                        <span class="badge bg-secondary">Draft</span>
                    @elseif($transfer->tth_status === '1')
                        <span class="badge bg-warning text-dark">Proses</span>
                    @else
                        <span class="badge bg-success">Selesai</span>
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold">Notes</label>
                <div>{{ $transfer->tth_notes ?? '-' }}</div>
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
                        <th>Warehouse Asal</th>
                        <th>Notes</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transfer->details as $detail)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $detail->itemDetail->itemd_code ?? '-' }}</td>
                            <td>{{ $detail->itemDetail->masterItem->masti_name ?? '-' }}</td>
                            <td>{{ $detail->warehouse->whsl_name ?? '-' }}</td>
                            <td>{{ $detail->ttd_notes ?? '-' }}</td>
                            <td>
                                @if($detail->ttd_is_canceled === '1')
                                    <span class="badge bg-danger">Dibatalkan</span>
                                @elseif($detail->ttd_status === '0')
                                    <span class="badge bg-secondary">Draft</span>
                                @else
                                    <span class="badge bg-success">Selesai</span>
                                @endif
                            </td>
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
