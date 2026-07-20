@extends('layouts.app')

@section('title', 'Tambah Unit of Measurement')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="fw-bold mb-0">TAMBAH UNIT BARU</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('uoms.store') }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="uom_code" class="form-label">Unit Code <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('uom_code') is-invalid @enderror" id="uom_code" name="uom_code" value="{{ old('uom_code') }}" required>
                    @error('uom_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label for="uom_name" class="form-label">Unit Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('uom_name') is-invalid @enderror" id="uom_name" name="uom_name" value="{{ old('uom_name') }}" required>
                    @error('uom_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Save</button>
                <a href="{{ route('uoms.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
