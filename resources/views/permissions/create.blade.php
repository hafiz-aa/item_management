@extends('layouts.app')

@section('title', 'Create Permission')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="fw-bold mb-0">Create New Permission</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('permissions.store') }}">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Permission Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="e.g., item.view" required>
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                <div class="form-text">Format: resource.action (e.g., item.view, item.create)</div>
            </div>
            <div class="mb-3">
                <label for="guard_name" class="form-label">Guard</label>
                <select class="form-select @error('guard_name') is-invalid @enderror" id="guard_name" name="guard_name">
                    <option value="web">web</option>
                    <option value="api">api</option>
                </select>
                @error('guard_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Save</button>
                <a href="{{ route('permissions.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
