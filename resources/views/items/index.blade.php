@extends('layouts.app')

@section('title', 'Items')

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
    $('#itemsTable').DataTable({
        responsive: true,
        processing: true,
        serverSide: false,
        order: [[0, 'desc']],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
        },
        columnDefs: [
            { orderable: false, targets: [0, -1] }
        ]
    });

    $('.btn-delete').on('click', function(e) {
        e.preventDefault();
        var form = $(this).closest('form');
        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: 'Apakah Anda yakin ingin menghapus item ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

    $('#selectAll').on('change', function() {
        $('.checkbox-item').prop('checked', $(this).prop('checked'));
    });

    $('#bulkDeleteBtn').on('click', function() {
        var ids = $('.checkbox-item:checked').map(function() { return $(this).val(); }).get();
        if (ids.length === 0) {
            Swal.fire('Peringatan', 'Pilih minimal satu item.', 'warning');
            return;
        }
        Swal.fire({
            title: 'Hapus Massal',
            text: 'Hapus ' + ids.length + ' item terpilih?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#bulkDeleteForm input[name="ids"]').val(ids);
                $('#bulkDeleteForm').submit();
            }
        });
    });
});
</script>
@endpush

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
        <h5 class="fw-bold mb-0">Items / Tabung</h5>
        <div class="d-flex gap-2">
            @can('item.create')
            <a href="{{ route('items.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg"></i> Add Item
            </a>
            @endcan
            @can('item.export')
            <a href="{{ route('export.items') . '?' . http_build_query(request()->except('page', 'per_page')) }}" class="btn btn-success btn-sm">
                <i class="bi bi-download"></i> Export
            </a>
            @endcan
        </div>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('items.index') }}" class="row g-2 mb-3">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search..." value="{{ $filters['search'] ?? '' }}">
            </div>
            <div class="col-md-2">
                <select name="warehouse_id" class="form-select form-select-sm">
                    <option value="">All Warehouses</option>
                    @foreach($warehouses as $w)
                        <option value="{{ $w->id }}" {{ ($filters['warehouse_id'] ?? '') == $w->id ? 'selected' : '' }}>{{ $w->nama_gudang }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Status</option>
                    <option value="Aktif" {{ ($filters['status'] ?? '') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="Tidak Aktif" {{ ($filters['status'] ?? '') == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="kategori" class="form-select form-select-sm">
                    <option value="">All Categories</option>
                    @foreach($kategoris as $k)
                        <option value="{{ $k }}" {{ ($filters['kategori'] ?? '') == $k ? 'selected' : '' }}>{{ $k }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-sm btn-primary w-100"><i class="bi bi-search"></i></button>
            </div>
            <div class="col-md-1">
                <a href="{{ route('items.index') }}" class="btn btn-sm btn-secondary w-100"><i class="bi bi-x-lg"></i></a>
            </div>
        </form>

        @can('item.delete')
        <form id="bulkDeleteForm" method="POST" action="{{ route('items.bulk-delete') }}">
            @csrf
            <input type="hidden" name="ids" value="">
        </form>
        @endcan

        <div class="table-responsive">
            <table id="itemsTable" class="table table-hover table-striped align-middle" style="width:100%">
                <thead class="table-dark">
                    <tr>
                        @can('item.delete')
                        <th width="40"><input type="checkbox" id="selectAll"></th>
                        @endcan
                        <th>Kode Tabung</th>
                        <th>Serial No</th>
                        <th>Kategori</th>
                        <th>Berat</th>
                        <th>Warehouse</th>
                        <th>Status</th>
                        <th>Rusak</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                    <tr>
                        @can('item.delete')
                        <td><input type="checkbox" class="checkbox-item" value="{{ $item->id }}"></td>
                        @endcan
                        <td><a href="{{ route('items.show', $item) }}" class="text-decoration-none fw-medium">{{ $item->kode_tabung }}</a></td>
                        <td>{{ $item->serial_no ?? '-' }}</td>
                        <td><span class="badge bg-info">{{ $item->kategori ?? '-' }}</span></td>
                        <td>{{ number_format($item->berat, 2) }} {{ $item->uom }}</td>
                        <td>{{ $item->warehouse?->nama_gudang ?? '-' }}</td>
                        <td>
                            <span class="badge bg-{{ $item->status == 'Aktif' ? 'success' : 'secondary' }}">
                                {{ $item->status }}
                            </span>
                        </td>
                        <td>
                            @if($item->rusak)
                                <span class="badge bg-danger">Ya</span>
                            @else
                                <span class="badge bg-success">Tidak</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('items.show', $item) }}" class="btn btn-sm btn-info text-white" title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @can('item.edit')
                                <a href="{{ route('items.edit', $item) }}" class="btn btn-sm btn-warning text-white" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @endcan
                                @can('item.delete')
                                <form action="{{ route('items.destroy', $item) }}" method="POST">
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
                        <td colspan="9" class="text-center py-4 text-muted">No items found.</td>
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
