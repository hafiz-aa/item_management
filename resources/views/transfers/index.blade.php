@extends('layouts.app')

@section('title', 'Transfer Item')

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
            $('#transferTable').DataTable({
                responsive: true,
                processing: true,
                serverSide: false,
                order: [
                    [1, 'desc']
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
                    text: 'Apakah Anda yakin ingin menghapus transfer ini?',
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
            <h5 class="fw-bold mb-0">Transfer Item</h5>
            <a href="{{ route('transfers.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg"></i> Tambah Transfer Baru
            </a>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('transfers.index') }}" class="row g-2 mb-3">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Search..."
                        value="{{ $filters['search'] ?? '' }}">
                </div>
                <div class="col-md-2">
                    <select name="branch_id" class="form-select form-select-sm">
                        <option value="">Semua Branch</option>
                        @foreach ($branches as $branch)
                            <option value="{{ $branch->branch_id }}"
                                {{ ($filters['branch_id'] ?? '') == $branch->branch_id ? 'selected' : '' }}>
                                {{ $branch->branch_code }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select form-select-sm">
                        <option value="">Semua Status</option>
                        <option value="0" {{ ($filters['status'] ?? '') === '0' ? 'selected' : '' }}>Belum Diterima
                        </option>
                        <option value="1" {{ ($filters['status'] ?? '') === '1' ? 'selected' : '' }}>Diterima Sebagian
                        </option>
                        <option value="2" {{ ($filters['status'] ?? '') === '2' ? 'selected' : '' }}>Diterima Semua
                        </option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-sm btn-primary w-100"><i class="bi bi-search"></i></button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('transfers.index') }}" class="btn btn-sm btn-secondary w-100"><i
                            class="bi bi-x-lg"></i></a>
                </div>
            </form>

            <div class="table-responsive">
                <table id="transferTable" class="table table-hover table-striped align-middle" style="width:100%">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Transaction No</th>
                            <th>Date</th>
                            <th>BA No</th>
                            <th>Ref No</th>
                            <th>From</th>
                            <th>To</th>
                            <th>Status</th>
                            <th>Canceled</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transfers as $transfer)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="fw-medium">{{ $transfer->tth_code }}</td>
                                <td>{{ $transfer->tth_date?->format('d/m/Y') ?? '-' }}</td>
                                <td>{{ $transfer->tth_ba_no ?? '-' }}</td>
                                <td>{{ $transfer->tth_po_no ?? '-' }}</td>
                                <td>{{ $transfer->branch->branch_name ?? '-' }}</td>
                                <td>{{ $transfer->branchTo->branch_name ?? '-' }}</td>
                                <td>
                                    @if ($transfer->tth_status === '0')
                                        <span class="badge bg-secondary">Belum Diterima</span>
                                    @elseif($transfer->tth_status === '1')
                                        <span class="badge bg-warning text-dark">Diterima Sebagian</span>
                                    @else
                                        <span class="badge bg-success">Diterima Semua</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($transfer->tth_is_canceled === '1')
                                        <span class="badge bg-danger">Ya</span>
                                    @else
                                        <span class="badge bg-success">Tidak</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('transfers.show', $transfer) }}"
                                            class="btn btn-sm btn-info text-white" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('transfers.edit', $transfer) }}"
                                            class="btn btn-sm btn-warning text-white" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('transfers.destroy', $transfer) }}" method="POST">
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
                                <td colspan="10" class="text-center py-4 text-muted">Tidak ada data transfer.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-3">
                {{ $transfers->withQueryString()->links() }}
            </div>
        </div>
    </div>
@endsection
