@extends('layouts.app')

@section('title', 'Report Position Item')

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
            $('#reportTable').DataTable({
                responsive: true,
                order: [
                    [1, 'asc']
                ],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
                },
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
        <div class="card-header bg-white py-3">
            <h5 class="fw-bold mb-0">Report Position Item</h5>
        </div>
        <div class="card-body">
            @include('reports.partials.filter', [
                'action' => route('reports.position'),
                'reportType' => 'position',
                'extra' => '<div class="col-md-2"><select name="whsl_id" class="form-select form-select-sm"><option value="">Semua Warehouse</option>'.collect($warehouses)->map(function($w) use ($filters) { return '<option value="'.$w->whsl_id.'"'.(($filters['whsl_id'] ?? '') == $w->whsl_id ? ' selected' : '').'>'.$w->whsl_name.'</option>'; })->implode('').'</select></div>',
            ])

            <div class="table-responsive">
                <table id="reportTable" class="table table-hover table-striped align-middle" style="width:100%">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Item Code</th>
                            <th>Item Name</th>
                            <th>Serial No</th>
                            <th>Capacity</th>
                            <th>UoM</th>
                            <th>Branch</th>
                            <th>Warehouse</th>
                            <th>Qty</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rows as $row)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="fw-medium">{{ $row->itemd_code }}</td>
                                <td>{{ $row->masti_name ?? '-' }}</td>
                                <td>{{ $row->itemd_serial_no ?? '-' }}</td>
                                <td>{{ $row->itemd_capacity ?? '-' }}</td>
                                <td>{{ $row->uom_name ?? '-' }}</td>
                                <td>{{ $row->branch_name ?? '-' }}</td>
                                <td>{{ $row->whsl_name ?? '-' }}</td>
                                <td>{{ $row->itemd_qty }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4 text-muted">Tidak ada data item.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-3">
                {{ $rows->withQueryString()->links() }}
            </div>
        </div>
    </div>
@endsection
