@extends('layouts.app')

@section('title', 'Tambah Item Description')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="fw-bold mb-0">TAMBAH ITEM DESCRIPTION BARU</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('item-descriptions.store') }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="item_code" class="form-label">Kode Item <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('item_code') is-invalid @enderror" id="item_code" name="item_code" value="{{ old('item_code') }}" required>
                    @error('item_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label for="item_description" class="form-label">Deskripsi Item</label>
                    <input type="text" class="form-control @error('item_description') is-invalid @enderror" id="item_description" name="item_description" value="{{ old('item_description') }}">
                    @error('item_description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-2">
                    <label for="capacity" class="form-label">Kapasitas <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" class="form-control @error('capacity') is-invalid @enderror" id="capacity" name="capacity" value="{{ old('capacity', 0) }}" required>
                    @error('capacity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-2">
                    <label for="uom" class="form-label">UOM <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('uom') is-invalid @enderror" id="uom" name="uom" value="{{ old('uom', 'Kg') }}" required>
                    @error('uom') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="row g-3 mt-2">
                <div class="col-md-4">
                    <label for="category_id" class="form-label">Kategori</label>
                    <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->category_id }}" {{ old('category_id') == $cat->category_id ? 'selected' : '' }}>
                                {{ $cat->category_code }} - {{ $cat->category_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Save</button>
                <a href="{{ route('item-descriptions.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
