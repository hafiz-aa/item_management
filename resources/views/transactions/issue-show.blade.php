@extends('layouts.app')

@section('title', 'Detail Issue')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0">Issue: {{ $issue->issuingh_code }}</h5>
        <div class="d-flex gap-2">
            <a href="{{ route('transactions.issue.edit', $issue) }}" class="btn btn-sm btn-warning text-white"><i class="bi bi-pencil me-1"></i> Edit</a>
            <a href="{{ route('transactions.issue.index') }}" class="btn btn-sm btn-secondary"><i class="bi bi-arrow-left me-1"></i> Back</a>
        </div>
    </div>
    <div class="card-body">
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <label class="form-label fw-bold">Code</label>
                <div>{{ $issue->issuingh_code }}</div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Date</label>
                <div>{{ $issue->issuingh_date?->format('d/m/Y') ?? '-' }}</div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Branch</label>
                <div>{{ $issue->branch->branch_name ?? '-' }}</div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Type</label>
                <div>
                    @if($issue->issuingh_type === '1')
                        <span class="badge bg-info">Vendor</span>
                    @else
                        <span class="badge bg-primary">Customer</span>
                    @endif
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Customer / Vendor</label>
                <div>{{ $issue->customer->cust_name ?? $issue->issuingh_receiver_name ?? '-' }}</div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">PIC</label>
                <div>{{ $issue->employee->emp_name ?? '-' }}</div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">BA No</label>
                <div>{{ $issue->issuingh_ba_no ?? '-' }}</div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Purchase No</label>
                <div>{{ $issue->issuingh_po_no ?? '-' }}</div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">DO No</label>
                <div>{{ $issue->issuingh_do_no ?? '-' }}</div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Sent By</label>
                <div>{{ $issue->issuingh_sent_by ?? '-' }}</div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Vehicle No</label>
                <div>{{ $issue->issuingh_vehicle_no ?? '-' }}</div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Status</label>
                <div>
                    @if($issue->issuingh_status === '2')
                        <span class="badge bg-success">Selesai</span>
                    @elseif($issue->issuingh_status === '1')
                        <span class="badge bg-warning text-dark">Sebagian</span>
                    @else
                        <span class="badge bg-secondary">Belum</span>
                    @endif
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Canceled</label>
                <div>
                    @if($issue->issuingh_is_canceled === '1')
                        <span class="badge bg-danger">Ya</span>
                    @else
                        <span class="badge bg-success">Tidak</span>
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold">Notes</label>
                <div>{{ $issue->issuingh_notes ?? '-' }}</div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Created By</label>
                <div>{{ $issue->creator->users_names ?? '-' }}</div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Created Date</label>
                <div>{{ $issue->created_time?->format('d/m/Y H:i') ?? '-' }}</div>
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
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($issue->details as $detail)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $detail->itemDetail->itemd_code ?? '-' }}</td>
                            <td>{{ $detail->itemDetail->header->masti_name ?? '-' }}</td>
                            <td>{{ $detail->itemDetail->itemd_serial_no ?? '-' }}</td>
                            <td>{{ $detail->itemDetail->itemd_capacity ?? '-' }}</td>
                            <td>{{ $detail->itemDetail->uom->uom_name ?? $detail->itemDetail->header->uom->uom_name ?? '-' }}</td>
                            <td>{{ $detail->itemDetail->header->category->cati_name ?? '-' }}</td>
                            <td>{{ $detail->issuingd_qty }}</td>
                            <td>{{ $detail->issuingd_notes ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">Tidak ada detail item.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
