@extends('layouts.app')

@section('title', 'Report Broken Item')

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
            <h5 class="fw-bold mb-0">Report Broken Item</h5>
        </div>
        <div class="card-body">
            @include('reports.partials.filter', [
                'action' => route('reports.broken'),
                'reportType' => 'broken',
                'extra' => '<div class="col-md-2"><select name="status" class="form-select form-select-sm"><option value="">Semua Status</option><option value="0" '.((($filters['status'] ?? '') === '0') ? 'selected' : '').'>Belum</option><option value="1" '.((($filters['status'] ?? '') === '1') ? 'selected' : '').'>Proses</option><option value="2" '.((($filters['status'] ?? '') === '2') ? 'selected' : '').'>Selesai</option></select></div>',
            ])

            <div class="table-responsive">
                <table id="reportTable" class="table table-hover table-striped align-middle" style="width:100%">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Broken No</th>
                            <th>Date</th>
                            <th>Item Code</th>
                            <th>Item Name</th>
                            <th>Serial No</th>
                            <th>Qty</th>
                            <th>Write-off</th>
                            <th>Disposed</th>
                            <th>Status</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rows as $row)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="fw-medium">{{ $row->brokh_code }}</td>
                                <td>{{ \Illuminate\Support\Carbon::parse($row->brokh_date)->format('d/m/Y') }}</td>
                                <td>{{ $row->itemd_code }}</td>
                                <td>{{ $row->masti_name ?? '-' }}</td>
                                <td>{{ $row->itemd_serial_no ?? '-' }}</td>
                                <td>{{ $row->brokd_qty }}</td>
                                <td>
                                    @if($row->brokd_is_wo === '1')
                                        <span class="badge bg-danger">Ya</span>
                                    @else
                                        <span class="badge bg-success">Tidak</span>
                                    @endif
                                </td>
                                <td>
                                    @if($row->brokd_is_dispossed === '1')
                                        <span class="badge bg-danger">Ya</span>
                                    @else
                                        <span class="badge bg-success">Tidak</span>
                                    @endif
                                </td>
                                <td>
                                    @if($row->brokh_status === '2')
                                        <span class="badge bg-success">Selesai</span>
                                    @elseif($row->brokh_status === '1')
                                        <span class="badge bg-warning text-dark">Proses</span>
                                    @else
                                        <span class="badge bg-secondary">Belum</span>
                                    @endif
                                </td>
                                <td>{{ $row->brokd_notes ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center py-4 text-muted">Tidak ada data broken.</td>
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
