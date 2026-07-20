@extends('layouts.app')

@section('title', 'Tambah Customer Type')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="fw-bold mb-0">TAMBAH TIPE CUSTOMER BARU</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('customer-types.store') }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="custtp_code" class="form-label">Kode Tipe <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('custtp_code') is-invalid @enderror" id="custtp_code" name="custtp_code" value="{{ old('custtp_code') }}" required>
                    @error('custtp_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label for="custtp_name" class="form-label">Nama Tipe <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('custtp_name') is-invalid @enderror" id="custtp_name" name="custtp_name" value="{{ old('custtp_name') }}" required>
                    @error('custtp_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label for="custtp_is_active" class="form-label">Status <span class="text-danger">*</span></label>
                    <select class="form-select @error('custtp_is_active') is-invalid @enderror" id="custtp_is_active" name="custtp_is_active" required>
                        <option value="0" {{ old('custtp_is_active') === '0' ? 'selected' : '' }}>Active</option>
                        <option value="1" {{ old('custtp_is_active') === '1' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('custtp_is_active') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Save</button>
                <a href="{{ route('customer-types.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
