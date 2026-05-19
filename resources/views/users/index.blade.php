@extends('layouts.app')

@section('title', 'Users')

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    $('.btn-delete').on('click', function(e) {
        e.preventDefault();
        var form = $(this).closest('form');
        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: 'Apakah Anda yakin ingin menghapus user ini?',
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
        <h5 class="fw-bold mb-0">Users</h5>
        @can('user.manage')
        <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> Add User</a>
        @endcan
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Roles</th>
                        <th>Warehouses</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $u)
                    <tr>
                        <td class="fw-medium">{{ $u->name }}</td>
                        <td>{{ $u->email }}</td>
                        <td>
                            @foreach($u->roles as $role)
                                <span class="badge bg-primary me-1">{{ $role->name }}</span>
                            @endforeach
                            @if($u->roles->isEmpty()) <span class="text-muted">-</span> @endif
                        </td>
                        <td>
                            @foreach($u->warehouses as $w)
                                <span class="badge bg-secondary me-1">{{ $w->kode_gudang }}</span>
                            @endforeach
                            @if($u->warehouses->isEmpty()) <span class="text-muted">-</span> @endif
                        </td>
                        <td>
                            <span class="badge bg-{{ $u->is_active ? 'success' : 'danger' }}">
                                {{ $u->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                @can('user.manage')
                                <a href="{{ route('users.edit', $u) }}" class="btn btn-sm btn-warning text-white" title="Edit"><i class="bi bi-pencil"></i></a>
                                @if(!$u->hasRole('Super Admin'))
                                <form action="{{ route('users.destroy', $u) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger btn-delete" title="Delete"><i class="bi bi-trash"></i></button>
                                </form>
                                @endif
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-4 text-muted">No users found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center mt-3">{{ $users->links() }}</div>
    </div>
</div>
@endsection
