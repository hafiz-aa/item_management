@extends('layouts.app')

@section('title', 'Roles')

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    $('.btn-delete').on('click', function(e) {
        e.preventDefault();
        var form = $(this).closest('form');
        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: 'Apakah Anda yakin ingin menghapus role ini?',
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
        <h5 class="fw-bold mb-0">Roles</h5>
        @can('role.manage')
        <a href="{{ route('roles.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> Add Role</a>
        @endcan
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-striped align-middle">
                <thead class="table-dark">
                    <tr><th>Name</th><th>Permissions</th><th>Users Count</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    @forelse($roles as $role)
                    <tr>
                        <td class="fw-medium">{{ $role->name }}</td>
                        <td>
                            @foreach($role->permissions->take(5) as $perm)
                                <span class="badge bg-info me-1">{{ $perm->name }}</span>
                            @endforeach
                            @if($role->permissions->count() > 5)
                                <span class="badge bg-secondary">+{{ $role->permissions->count() - 5 }} more</span>
                            @elseif($role->permissions->isEmpty())
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>{{ $role->users_count ?? $role->users()->count() }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                @can('role.manage')
                                <a href="{{ route('roles.edit', $role) }}" class="btn btn-sm btn-warning text-white" title="Edit"><i class="bi bi-pencil"></i></a>
                                @if(!in_array($role->name, ['Super Admin', 'Admin Gudang', 'Staff Gudang', 'Viewer']))
                                <form action="{{ route('roles.destroy', $role) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger btn-delete" title="Delete"><i class="bi bi-trash"></i></button>
                                </form>
                                @endif
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center py-4 text-muted">No roles found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center mt-3">{{ $roles->links() }}</div>
    </div>
</div>
@endsection
