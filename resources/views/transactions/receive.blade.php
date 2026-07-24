@extends('layouts.app')

@section('title', 'Receive Item')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
@endpush

@push('scripts')
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#receiveTable').DataTable({
                responsive: true,
                processing: true,
                serverSide: false,
                order: [[3, 'desc']],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
                },
                pageLength: 25,
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua"]]
            });
        });
    </script>
@endpush

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
            <h5 class="fw-bold mb-0">Receive Item</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('transactions.receive') }}" class="row g-2 mb-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Search..."
                        value="{{ $filters['search'] ?? '' }}">
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select form-select-sm">
                        <option value="">Semua Status</option>
                        <option value="0" {{ ($filters['status'] ?? '') === '0' ? 'selected' : '' }}>Tidak</option>
                        <option value="1" {{ ($filters['status'] ?? '') === '1' ? 'selected' : '' }}>Ya</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-sm btn-primary w-100"><i class="bi bi-search"></i></button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('transactions.receive') }}" class="btn btn-sm btn-secondary w-100"><i class="bi bi-x-lg"></i></a>
                </div>
            </form>

            <div class="table-responsive">
                <table id="receiveTable" class="table table-hover table-striped align-middle" style="width:100%">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Transaction No</th>
                            <th>Reference No</th>
                            <th>Date</th>
                            <th>BA No</th>
                            <th>Purchase No</th>
                            <th>From Branch</th>
                            <th>Total Qty</th>
                            <th>Cancel</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($receives as $receive)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="fw-medium">{{ $receive->rth_code }}</td>
                                <td>{{ $receive->transferHeader->tth_code ?? '-' }}</td>
                                <td>{{ $receive->rth_date?->format('d/m/Y') ?? '-' }}</td>
                                <td>{{ $receive->rth_ba_no ?? '-' }}</td>
                                <td>{{ $receive->rth_po_no ?? '-' }}</td>
                                <td>{{ $receive->branchFrom->branch_name ?? '-' }}</td>
                                <td class="text-center"><span class="badge bg-info">{{ $receive->total_qty }}</span></td>
                                <td>
                                    @if($receive->rth_is_canceled === '1')
                                        <span class="badge bg-danger">Ya</span>
                                    @else
                                        <span class="badge bg-success">Tidak</span>
                                    @endif
                                </td>
                                <td>{{ $receive->rth_notes ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-4 text-muted">Tidak ada data receive.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-3">
                {{ $receives->withQueryString()->links() }}
            </div>
        </div>
    </div>
@endsection
