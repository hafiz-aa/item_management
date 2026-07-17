@extends('layouts.app')

@section('title', 'Tambah Item Category')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="fw-bold mb-0">TAMBAH KATEGORI ITEM BARU</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('item-categories.store') }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="category_code" class="form-label">Kode Kategori <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('category_code') is-invalid @enderror" id="category_code" name="category_code" value="{{ old('category_code') }}" required>
                    @error('category_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label for="category_name" class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('category_name') is-invalid @enderror" id="category_name" name="category_name" value="{{ old('category_name') }}" required>
                    @error('category_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label for="description" class="form-label">Deskripsi</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="1">{{ old('description') }}</textarea>
                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Save</button>
                <a href="{{ route('item-categories.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
