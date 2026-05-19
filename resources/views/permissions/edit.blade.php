@extends('layouts.app')

@section('title', 'Edit Permission')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="fw-bold mb-0">Edit Permission</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('permissions.update', $permission) }}">
            @csrf @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Permission Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $permission->name) }}" required>
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Update</button>
                <a href="{{ route('permissions.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
