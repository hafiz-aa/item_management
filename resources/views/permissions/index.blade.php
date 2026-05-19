@extends('layouts.app')

@section('title', 'Permissions')

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    $('.btn-delete').on('click', function(e) {
        e.preventDefault();
        var form = $(this).closest('form');
        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: 'Apakah Anda yakin ingin menghapus permission ini?',
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
        <h5 class="fw-bold mb-0">Permissions</h5>
        @can('permission.manage')
        <a href="{{ route('permissions.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> Add Permission</a>
        @endcan
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-striped align-middle">
                <thead class="table-dark">
                    <tr><th>Name</th><th>Guard</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    @forelse($permissions as $perm)
                    <tr>
                        <td class="fw-medium">{{ $perm->name }}</td>
                        <td><span class="badge bg-secondary">{{ $perm->guard_name }}</span></td>
                        <td>
                            <div class="d-flex gap-1">
                                @can('permission.manage')
                                <a href="{{ route('permissions.edit', $perm) }}" class="btn btn-sm btn-warning text-white" title="Edit"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('permissions.destroy', $perm) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger btn-delete" title="Delete"><i class="bi bi-trash"></i></button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="text-center py-4 text-muted">No permissions found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center mt-3">{{ $permissions->links() }}</div>
    </div>
</div>
@endsection
