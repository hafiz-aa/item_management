@extends('layouts.app')

@section('title', 'Item All Branches')

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
            $('#allBranchesTable').DataTable({
                responsive: true,
                processing: true,
                serverSide: false,
                order: [
                    [1, 'asc'],
                    [2, 'asc']
                ],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
                },
                columnDefs: [{
                    orderable: false,
                    targets: [0]
                }],
                pageLength: 25,
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "Semua"]
                ]
            });
        });
    </script>
@endpush

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
            <h5 class="fw-bold mb-0">Item All Branches</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('items.all-branches') }}" class="row g-2 mb-3">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control form-control-sm"
                        placeholder="Search item code, name, serial no..." value="{{ $filters['search'] ?? '' }}">
                </div>
                <div class="col-md-2">
                    <select name="branch_id" class="form-select form-select-sm">
                        <option value="">All Branches</option>
                        @foreach ($branches as $b)
                            <option value="{{ $b->branch_id }}"
                                {{ ($filters['branch_id'] ?? '') == $b->branch_id ? 'selected' : '' }}>{{ $b->branch_code }}
                                - {{ $b->branch_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="cati_id" class="form-select form-select-sm">
                        <option value="">All Categories</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->cati_id }}"
                                {{ ($filters['cati_id'] ?? '') == $cat->cati_id ? 'selected' : '' }}>{{ $cat->cati_code }} -
                                {{ $cat->cati_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select form-select-sm">
                        <option value="">All Status</option>
                        <option value="0" {{ ($filters['status'] ?? '') == '0' ? 'selected' : '' }}>Aktif</option>
                        <option value="1" {{ ($filters['status'] ?? '') == '1' ? 'selected' : '' }}>Tidak Aktif
                        </option>
                    </select>
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-sm btn-primary w-100"><i class="bi bi-search"></i></button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('items.all-branches') }}" class="btn btn-sm btn-secondary w-100"><i
                            class="bi bi-x-lg"></i> Reset</a>
                </div>
            </form>

            <div class="table-responsive">
                <table id="allBranchesTable" class="table table-hover table-striped align-middle" style="width:100%">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Branch</th>
                            <th>Item Code</th>
                            <th>Item Description</th>
                            <th>Serial No</th>
                            <th>Capacity</th>
                            <th>Qty</th>
                            <th>Acquired Date</th>
                            <th>Ages (Days)</th>
                            <th>Category</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = ($items->currentPage() - 1) * $items->perPage() + 1; @endphp
                        @forelse($items as $item)
                            @php
                                $acquired = $item->itemd_acquired_date
                                    ? \Carbon\Carbon::parse($item->itemd_acquired_date)
                                    : null;
                                $ages = $acquired ? $acquired->startOfDay()->diffInDays(now()->startOfDay()) : null;
                            @endphp
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>
                                    @if ($item->branch_code)
                                        <span class="badge bg-info">{{ $item->branch_code }}</span>
                                        {{ $item->branch_name }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="fw-medium">{{ $item->masti_code }}</td>
                                <td>{{ $item->masti_name ?? '-' }}</td>
                                <td>{{ $item->itemd_serial_no ?? ($item->itemd_code ?? '-') }}</td>
                                <td>{{ $item->itemd_capacity }} {{ $item->uom_code }}</td>
                                <td>{{ $item->itemd_qty }}</td>
                                <td>{{ $item->itemd_acquired_date ? \Carbon\Carbon::parse($item->itemd_acquired_date)->format('d/m/Y') : '-' }}
                                </td>
                                <td>
                                    @if ($ages !== null)
                                        <span class="badge bg-secondary">{{ number_format($ages) }} days</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($item->cati_code)
                                        <span class="badge bg-info">{{ $item->cati_code }}</span>
                                        {{ $item->cati_name }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $item->itemd_status == '0' ? 'success' : 'secondary' }}">
                                        {{ $item->itemd_status == '0' ? 'Aktif' : 'Tidak Aktif' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center py-4 text-muted">No data found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-3">
                {{ $items->withQueryString()->links() }}
            </div>
        </div>
    </div>
@endsection
