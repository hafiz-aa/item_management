@extends('layouts.app')

@section('title', 'Edit Role')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="fw-bold mb-0">Edit Role: {{ $role->name }}</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('roles.update', $role) }}">
            @csrf @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Role Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $role->name) }}" required>
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Permissions</label>
                @foreach($permissions as $group => $groupPermissions)
                <div class="card mb-2 border">
                    <div class="card-header bg-light py-2">
                        <h6 class="mb-0 text-capitalize">{{ $group }}</h6>
                    </div>
                    <div class="card-body py-2">
                        <div class="row">
                            @foreach($groupPermissions as $perm)
                            <div class="col-md-3 col-6 mb-1">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $perm->id }}" id="perm_{{ $perm->id }}" {{ $role->hasPermissionTo($perm->name) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="perm_{{ $perm->id }}">{{ $perm->name }}</label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Update</button>
                <a href="{{ route('roles.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
