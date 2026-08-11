@extends('layouts.app')

@section('title', 'Broken Item')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
@endpush

@push('scripts')
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            $('#brokenTable').DataTable({
                responsive: true,
                processing: true,
                serverSide: false,
                order: [
                    [2, 'desc']
                ],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
                },
                columnDefs: [{
                    orderable: false,
                    targets: [-1]
                }],
                pageLength: 25,
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "Semua"]
                ]
            });

            $('.btn-delete').on('click', function(e) {
                e.preventDefault();
                var form = $(this).closest('form');
                Swal.fire({
                    title: 'Konfirmasi Hapus',
                    text: 'Apakah Anda yakin ingin menghapus broken ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) form.submit();
                });
            });
        });
    </script>
@endpush

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
            <h5 class="fw-bold mb-0">Broken Item</h5>
            <a href="{{ route('transactions.broken.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg"></i> Tambah Broken Baru
            </a>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('transactions.broken.index') }}" class="row g-2 mb-3">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Search..."
                        value="{{ $filters['search'] ?? '' }}">
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select form-select-sm">
                        <option value="">Semua Status</option>
                        <option value="0" {{ ($filters['status'] ?? '') === '0' ? 'selected' : '' }}>Belum</option>
                        <option value="1" {{ ($filters['status'] ?? '') === '1' ? 'selected' : '' }}>Proses</option>
                        <option value="2" {{ ($filters['status'] ?? '') === '2' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="canceled" class="form-select form-select-sm">
                        <option value="">Semua Cancel</option>
                        <option value="0" {{ ($filters['canceled'] ?? '') === '0' ? 'selected' : '' }}>Tidak</option>
                        <option value="1" {{ ($filters['canceled'] ?? '') === '1' ? 'selected' : '' }}>Ya</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-sm btn-primary w-100"><i class="bi bi-search"></i></button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('transactions.broken.index') }}" class="btn btn-sm btn-secondary w-100"><i class="bi bi-x-lg"></i></a>
                </div>
            </form>

            <div class="table-responsive">
                <table id="brokenTable" class="table table-hover table-striped align-middle" style="width:100%">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Transaction No</th>
                            <th>Date</th>
                            <th>Total Qty</th>
                            <th>Status</th>
                            <th>Canceled</th>
                            <th>Notes</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($brokens as $broken)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="fw-medium">{{ $broken->brokh_code }}</td>
                                <td>{{ $broken->brokh_date?->format('d/m/Y') ?? '-' }}</td>
                                <td class="text-center"><span class="badge bg-info">{{ $broken->total_qty }}</span></td>
                                <td>
                                    @if ($broken->brokh_status === '2')
                                        <span class="badge bg-success">Selesai</span>
                                    @elseif($broken->brokh_status === '1')
                                        <span class="badge bg-warning text-dark">Proses</span>
                                    @else
                                        <span class="badge bg-secondary">Belum</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($broken->brokh_is_canceled === '1')
                                        <span class="badge bg-danger">Ya</span>
                                    @else
                                        <span class="badge bg-success">Tidak</span>
                                    @endif
                                </td>
                                <td class="text-truncate" style="max-width: 200px;" title="{{ $broken->brokh_notes ?? '' }}">
                                    {{ $broken->brokh_notes ?? '-' }}
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('transactions.broken.show', $broken) }}"
                                            class="btn btn-sm btn-info text-white" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('transactions.broken.edit', $broken) }}"
                                            class="btn btn-sm btn-warning text-white" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('transactions.broken.destroy', $broken) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger btn-delete" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">Tidak ada data broken.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-3">
                {{ $brokens->withQueryString()->links() }}
            </div>
        </div>
    </div>
@endsection
