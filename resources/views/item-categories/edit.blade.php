@extends('layouts.app')

@section('title', 'Edit Item Category')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="fw-bold mb-0">Edit: {{ $category->cati_code }} - {{ $category->cati_name }}</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('item-categories.update', $category) }}">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="cati_code" class="form-label">Kode Kategori <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('cati_code') is-invalid @enderror" id="cati_code" name="cati_code" value="{{ old('cati_code', $category->cati_code) }}" required>
                    @error('cati_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label for="cati_name" class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('cati_name') is-invalid @enderror" id="cati_name" name="cati_name" value="{{ old('cati_name', $category->cati_name) }}" required>
                    @error('cati_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label for="cati_notes" class="form-label">Deskripsi</label>
                    <textarea class="form-control @error('cati_notes') is-invalid @enderror" id="cati_notes" name="cati_notes" rows="1">{{ old('cati_notes', $category->cati_notes) }}</textarea>
                    @error('cati_notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Update</button>
                <a href="{{ route('item-categories.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
