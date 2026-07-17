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
                    <label for="masti_code" class="form-label">Kode Item <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('masti_code') is-invalid @enderror" id="masti_code" name="masti_code" value="{{ old('masti_code') }}" required>
                    @error('masti_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label for="masti_name" class="form-label">Deskripsi Item</label>
                    <input type="text" class="form-control @error('masti_name') is-invalid @enderror" id="masti_name" name="masti_name" value="{{ old('masti_name') }}">
                    @error('masti_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-2">
                    <label for="masti_capacity" class="form-label">Kapasitas <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" class="form-control @error('masti_capacity') is-invalid @enderror" id="masti_capacity" name="masti_capacity" value="{{ old('masti_capacity', 0) }}" required>
                    @error('masti_capacity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-2">
                    <label for="uom_id_1" class="form-label">UOM <span class="text-danger">*</span></label>
                    <select class="form-select @error('uom_id_1') is-invalid @enderror" id="uom_id_1" name="uom_id_1" required>
                        <option value="">-- Pilih UoM --</option>
                        @foreach($uoms as $u)
                            <option value="{{ $u->uom_id }}" {{ old('uom_id_1') == $u->uom_id ? 'selected' : '' }}>{{ $u->uom_code }} - {{ $u->uom_name }}</option>
                        @endforeach
                    </select>
                    @error('uom_id_1') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="row g-3 mt-2">
                <div class="col-md-4">
                    <label for="cati_id" class="form-label">Kategori</label>
                    <select class="form-select @error('cati_id') is-invalid @enderror" id="cati_id" name="cati_id">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->cati_id }}" {{ old('cati_id') == $cat->cati_id ? 'selected' : '' }}>
                                {{ $cat->cati_code }} - {{ $cat->cati_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('cati_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
