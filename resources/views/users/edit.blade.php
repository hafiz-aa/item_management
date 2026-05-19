@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="fw-bold mb-0">Edit User: {{ $user->name }}</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('users.update', $user) }}">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="row g-3 mt-2">
                <div class="col-md-6">
                    <label for="password" class="form-label">Password (kosongkan jika tidak diubah)</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                </div>
            </div>
            <div class="row g-3 mt-2">
                <div class="col-md-6">
                    <label for="roles" class="form-label">Roles <span class="text-danger">*</span></label>
                    <select class="form-select @error('roles') is-invalid @enderror" id="roles" name="roles[]" multiple required size="4">
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>{{ $role->name }}</option>
                        @endforeach
                    </select>
                    @error('roles') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label for="warehouses" class="form-label">Warehouse Access</label>
                    <select class="form-select @error('warehouses') is-invalid @enderror" id="warehouses" name="warehouses[]" multiple size="4">
                        @foreach($warehouses as $w)
                            <option value="{{ $w->id }}" {{ $user->warehouses->contains($w->id) ? 'selected' : '' }}>{{ $w->nama_gudang }}</option>
                        @endforeach
                    </select>
                    @error('warehouses') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Update</button>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
