@extends('layouts.app')

@section('title', 'Detail Change Item Description')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0">Change Item Description: {{ $change->cidh_code }}</h5>
        <div class="d-flex gap-2">
            <a href="{{ route('transactions.change-description.edit', $change) }}" class="btn btn-sm btn-warning text-white"><i class="bi bi-pencil me-1"></i> Edit</a>
            <a href="{{ route('transactions.change-description.index') }}" class="btn btn-sm btn-secondary"><i class="bi bi-arrow-left me-1"></i> Back</a>
        </div>
    </div>
    <div class="card-body">
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <label class="form-label fw-bold">Code</label>
                <div>{{ $change->cidh_code }}</div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Date</label>
                <div>{{ $change->cidh_date?->format('d/m/Y') ?? '-' }}</div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Branch</label>
                <div>{{ $change->branch->branch_name ?? '-' }}</div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Canceled</label>
                <div>
                    @if($change->cidh_is_canceled === '1')
                        <span class="badge bg-danger">Ya</span>
                    @else
                        <span class="badge bg-success">Tidak</span>
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold">Notes</label>
                <div>{{ $change->cidh_notes ?? '-' }}</div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Created By</label>
                <div>{{ $change->creator->users_names ?? '-' }}</div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Created Date</label>
                <div>{{ $change->created_time?->format('d/m/Y H:i') ?? '-' }}</div>
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
                        <th>Old Description</th>
                        <th>New Description</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($change->details as $detail)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $detail->itemDetail->itemd_code ?? '-' }}</td>
                            <td>{{ $detail->itemDetail->header->masti_name ?? '-' }}</td>
                            <td>{{ $detail->itemDetail->itemd_serial_no ?? '-' }}</td>
                            <td>
                                @if($detail->oldMaster)
                                    <span class="badge bg-secondary">{{ $detail->oldMaster->masti_code }}</span>
                                    {{ $detail->oldMaster->masti_name }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($detail->newMaster)
                                    <span class="badge bg-primary">{{ $detail->newMaster->masti_code }}</span>
                                    {{ $detail->newMaster->masti_name }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $detail->cidd_notes ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">Tidak ada detail item.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
