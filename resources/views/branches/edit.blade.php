@extends('layouts.app')

@section('title', 'Edit Branch')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="fw-bold mb-0">Edit: {{ $branch->branch_code }} - {{ $branch->branch_name }}</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('branches.update', $branch) }}">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="branch_code" class="form-label">Kode Branch <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('branch_code') is-invalid @enderror" id="branch_code" name="branch_code" value="{{ old('branch_code', $branch->branch_code) }}" required>
                    @error('branch_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label for="branch_name" class="form-label">Nama Branch <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('branch_name') is-invalid @enderror" id="branch_name" name="branch_name" value="{{ old('branch_name', $branch->branch_name) }}" required>
                    @error('branch_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label for="branch_is_headquarter" class="form-label">&nbsp;</label>
                    <div class="form-check form-switch mt-2">
                        <input class="form-check-input" type="checkbox" role="switch" id="branch_is_headquarter" name="branch_is_headquarter" value="1" {{ old('branch_is_headquarter', $branch->branch_is_headquarter) ? 'checked' : '' }}>
                        <label class="form-check-label" for="branch_is_headquarter">Headquarter</label>
                    </div>
                </div>
            </div>
            <div class="row g-3 mt-2">
                <div class="col-md-12">
                    <label for="branch_address" class="form-label">Alamat</label>
                    <textarea class="form-control @error('branch_address') is-invalid @enderror" id="branch_address" name="branch_address" rows="3">{{ old('branch_address', $branch->branch_address) }}</textarea>
                    @error('branch_address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Update</button>
                <a href="{{ route('branches.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
