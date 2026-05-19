@extends('layouts.app')

@section('title', 'Warehouses')

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    $('.btn-delete').on('click', function(e) {
        e.preventDefault();
        var form = $(this).closest('form');
        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: 'Apakah Anda yakin ingin menghapus gudang ini?',
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
        <h5 class="fw-bold mb-0">Warehouses / Gudang</h5>
        @can('warehouse.manage')
        <a href="{{ route('warehouses.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> Add Warehouse</a>
        @endcan
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Kode Gudang</th>
                        <th>Nama Gudang</th>
                        <th>Kota</th>
                        <th>Penanggung Jawab</th>
                        <th>Telepon</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($warehouses as $w)
                    <tr>
                        <td class="fw-medium">{{ $w->kode_gudang }}</td>
                        <td>{{ $w->nama_gudang }}</td>
                        <td>{{ $w->kota ?? '-' }}</td>
                        <td>{{ $w->penanggung_jawab ?? '-' }}</td>
                        <td>{{ $w->telepon ?? '-' }}</td>
                        <td>
                            <span class="badge bg-{{ $w->status == 'Aktif' ? 'success' : 'secondary' }}">{{ $w->status }}</span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                @can('warehouse.manage')
                                <a href="{{ route('warehouses.edit', $w) }}" class="btn btn-sm btn-warning text-white" title="Edit"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('warehouses.destroy', $w) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger btn-delete" title="Delete"><i class="bi bi-trash"></i></button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-4 text-muted">No warehouses found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
