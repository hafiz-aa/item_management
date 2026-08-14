@extends('layouts.app')

@section('title', 'Report Aging')

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
                    [5, 'desc']
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
    @php
        $buckets = ['1' => '< 30 hari', '2' => '30 - 60 hari', '3' => '60 - 90 hari', '4' => '90 - 180 hari', '5' => '> 180 hari'];
    @endphp
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="fw-bold mb-0">Report Aging</h5>
        </div>
        <div class="card-body">
            @include('reports.partials.filter', [
                'action' => route('reports.aging'),
                'extra' => '<div class="col-md-2"><select name="aging" class="form-select form-select-sm"><option value="">Semua Aging</option>'.collect($buckets)->map(function($label, $key) use ($filters) { return '<option value="'.$key.'"'.(($filters['aging'] ?? '') === $key ? ' selected' : '').'>'.$label.'</option>'; })->implode('').'</select></div>',
            ])

            <div class="table-responsive">
                <table id="reportTable" class="table table-hover table-striped align-middle" style="width:100%">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Item Code</th>
                            <th>Item Name</th>
                            <th>Serial No</th>
                            <th>Acquired Date</th>
                            <th>Days</th>
                            <th>Aging</th>
                            <th>Branch</th>
                            <th>Warehouse</th>
                            <th>Qty</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rows as $row)
                            @php
                                $acquired = $row->itemd_acquired_date ? \Illuminate\Support\Carbon::parse($row->itemd_acquired_date) : null;
                                $days = $acquired ? $acquired->diffInDays(\Illuminate\Support\Carbon::now(), false) : null;
                                $bucket = null;
                                if ($days !== null) {
                                    if ($days < 30) { $bucket = ['< 30 hari', 'bg-success']; }
                                    elseif ($days < 60) { $bucket = ['30 - 60 hari', 'bg-info text-dark']; }
                                    elseif ($days < 90) { $bucket = ['60 - 90 hari', 'bg-warning text-dark']; }
                                    elseif ($days < 180) { $bucket = ['90 - 180 hari', 'bg-primary']; }
                                    else { $bucket = ['> 180 hari', 'bg-danger']; }
                                }
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="fw-medium">{{ $row->itemd_code }}</td>
                                <td>{{ $row->masti_name ?? '-' }}</td>
                                <td>{{ $row->itemd_serial_no ?? '-' }}</td>
                                <td>{{ $acquired?->format('d/m/Y') ?? '-' }}</td>
                                <td>{{ $days ?? '-' }}</td>
                                <td>
                                    @if($bucket)
                                        <span class="badge {{ $bucket[1] }}">{{ $bucket[0] }}</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $row->branch_name ?? '-' }}</td>
                                <td>{{ $row->whsl_name ?? '-' }}</td>
                                <td>{{ $row->itemd_qty }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-4 text-muted">Tidak ada data item.</td>
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
