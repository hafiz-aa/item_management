@extends('layouts.app')

@section('title', 'Report Write-off Item')

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
                    [2, 'desc']
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
            <h5 class="fw-bold mb-0">Report Write-off Item</h5>
        </div>
        <div class="card-body">
            @include('reports.partials.filter', ['action' => route('reports.write-off'), 'reportType' => 'write-off'])

            <div class="table-responsive">
                <table id="reportTable" class="table table-hover table-striped align-middle" style="width:100%">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Write-off No</th>
                            <th>Date</th>
                            <th>Source</th>
                            <th>Item Code</th>
                            <th>Item Name</th>
                            <th>Serial No</th>
                            <th>Qty</th>
                            <th>Canceled</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rows as $row)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="fw-medium">{{ $row->woh_code }}</td>
                                <td>{{ \Illuminate\Support\Carbon::parse($row->woh_date)->format('d/m/Y') }}</td>
                                <td>
                                    @if($row->woh_sources === '1')
                                        <span class="badge bg-info text-dark">Change Description</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Broken</span>
                                    @endif
                                </td>
                                <td>{{ $row->itemd_code }}</td>
                                <td>{{ $row->masti_name ?? '-' }}</td>
                                <td>{{ $row->itemd_serial_no ?? '-' }}</td>
                                <td>{{ $row->wod_qty }}</td>
                                <td>
                                    @if($row->woh_is_canceled === '1')
                                        <span class="badge bg-danger">Ya</span>
                                    @else
                                        <span class="badge bg-success">Tidak</span>
                                    @endif
                                </td>
                                <td>{{ $row->wod_notes ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-4 text-muted">Tidak ada data write-off.</td>
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
