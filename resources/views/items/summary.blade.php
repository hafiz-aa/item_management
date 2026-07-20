@extends('layouts.app')

@section('title', 'Item Summary')

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
            $('#summaryTable').DataTable({
                responsive: true,
                processing: true,
                serverSide: false,
                order: [[1, 'asc']],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
                },
                columnDefs: [{
                    orderable: false,
                    targets: [0]
                }],
                pageLength: 25,
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua"]]
            });
        });
    </script>
@endpush

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
            <h5 class="fw-bold mb-0">Item Summary</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('items.summary') }}" class="row g-2 mb-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Search item code, name..."
                        value="{{ $filters['search'] ?? '' }}">
                </div>
                <div class="col-md-3">
                    <select name="cati_id" class="form-select form-select-sm">
                        <option value="">All Categories</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->cati_id }}"
                                {{ ($filters['cati_id'] ?? '') == $cat->cati_id ? 'selected' : '' }}>{{ $cat->cati_code }} - {{ $cat->cati_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-sm btn-primary w-100"><i class="bi bi-search"></i></button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('items.summary') }}" class="btn btn-sm btn-secondary w-100"><i class="bi bi-x-lg"></i> Reset</a>
                </div>
            </form>

            <div class="table-responsive">
                <table id="summaryTable" class="table table-hover table-striped align-middle" style="width:100%">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Item Code</th>
                            <th>Item Description</th>
                            <th>Capacity</th>
                            <th>Category</th>
                            <th class="text-end">Qty</th>
                            <th class="text-end">Qty Active</th>
                            <th class="text-end">Qty Broken</th>
                            <th class="text-end">Qty Write-off</th>
                            <th class="text-end">Qty Sold</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = ($items->currentPage() - 1) * $items->perPage() + 1; @endphp
                        @forelse($items as $item)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td class="fw-medium">{{ $item->masti_code }}</td>
                                <td>{{ $item->masti_name ?? '-' }}</td>
                                <td>{{ $item->masti_capacity }} {{ $item->uom_code }}</td>
                                <td>
                                    @if($item->cati_code)
                                        <span class="badge bg-info">{{ $item->cati_code }}</span>
                                        {{ $item->cati_name }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-end"><span class="badge bg-dark">{{ number_format($item->qty) }}</span></td>
                                <td class="text-end"><span class="badge bg-success">{{ number_format($item->qty_active) }}</span></td>
                                <td class="text-end"><span class="badge bg-danger">{{ number_format($item->qty_broken) }}</span></td>
                                <td class="text-end"><span class="badge bg-warning text-dark">{{ number_format($item->qty_writeoff) }}</span></td>
                                <td class="text-end"><span class="badge bg-info">{{ number_format($item->qty_sold) }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-4 text-muted">No data found.</td>
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
