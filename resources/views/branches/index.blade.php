@extends('layouts.app')

@section('title', 'Branches')

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
            $('#branchesTable').DataTable({
                responsive: true,
                processing: true,
                serverSide: false,
                order: [[0, 'asc']],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
                },
                columnDefs: [{
                    orderable: false,
                    targets: [-1]
                }]
            });

            $('.btn-delete').on('click', function(e) {
                e.preventDefault();
                var form = $(this).closest('form');
                Swal.fire({
                    title: 'Konfirmasi Hapus',
                    text: 'Apakah Anda yakin ingin menghapus branch ini?',
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
            <h5 class="fw-bold mb-0">Branches</h5>
            <div class="d-flex gap-2">
                @can('branch.create')
                    <a href="{{ route('branches.create') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-lg"></i> Add Branch
                    </a>
                @endcan
            </div>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('branches.index') }}" class="row g-2 mb-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Search..."
                        value="{{ $filters['search'] ?? '' }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-sm btn-primary w-100"><i class="bi bi-search"></i></button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('branches.index') }}" class="btn btn-sm btn-secondary w-100"><i
                            class="bi bi-x-lg"></i></a>
                </div>
            </form>

            <div class="table-responsive">
                <table id="branchesTable" class="table table-hover table-striped align-middle" style="width:100%">
                    <thead class="table-dark">
                        <tr>
                            <th>Branch Code</th>
                            <th>Branch Name</th>
                            <th>HQ</th>
                            <th>Address</th>
                            <th>Warehouses</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($branches as $branch)
                            <tr>
                                <td class="fw-medium">{{ $branch->branch_code }}</td>
                                <td>{{ $branch->branch_name }}</td>
                                <td>
                                    @if($branch->branch_is_headquarter == '0')
                                        <span class="badge bg-primary">HQ</span>
                                    @else
                                        <span class="badge bg-info">Branch</span>
                                    @endif
                                </td>
                                <td>{{ $branch->branch_address ?? '-' }}</td>
                                <td><span class="badge bg-info">{{ $branch->warehouses_count }}</span></td>
                                <td>
                                    <div class="d-flex gap-1">
                                        @can('branch.edit')
                                            <a href="{{ route('branches.edit', $branch) }}" class="btn btn-sm btn-warning text-white" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        @endcan
                                        @can('branch.delete')
                                            <form action="{{ route('branches.destroy', $branch) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger btn-delete" title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">No branches found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-3">
                {{ $branches->withQueryString()->links() }}
            </div>
        </div>
    </div>
@endsection
