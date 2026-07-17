@extends('layouts.app')

@section('title', 'Edit Item')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="fw-bold mb-0">Edit Item: {{ $item->masti_code }}</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('items.update', $item) }}">
            @csrf @method('PUT')

            @php $detail = $item->details->first(); @endphp

            <h6 class="fw-bold text-primary mb-3">Header Information</h6>
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="masti_code" class="form-label">Item Code <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('masti_code') is-invalid @enderror" id="masti_code" name="masti_code" value="{{ old('masti_code', $item->masti_code) }}" required>
                    @error('masti_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label for="masti_name" class="form-label">Item Name</label>
                    <input type="text" class="form-control @error('masti_name') is-invalid @enderror" id="masti_name" name="masti_name" value="{{ old('masti_name', $item->masti_name) }}">
                    @error('masti_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-2">
                    <label for="masti_capacity" class="form-label">Capacity</label>
                    <input type="number" step="0.01" class="form-control @error('masti_capacity') is-invalid @enderror" id="masti_capacity" name="masti_capacity" value="{{ old('masti_capacity', $item->masti_capacity) }}">
                    @error('masti_capacity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-2">
                    <label for="uom_id_1" class="form-label">UoM 1</label>
                    <select class="form-select @error('uom_id_1') is-invalid @enderror" id="uom_id_1" name="uom_id_1">
                        <option value="">-- Pilih UoM --</option>
                        @foreach($uoms as $u)
                            <option value="{{ $u->uom_id }}" {{ (old('uom_id_1', $item->uom_id_1)) == $u->uom_id ? 'selected' : '' }}>{{ $u->uom_code }} - {{ $u->uom_name }}</option>
                        @endforeach
                    </select>
                    @error('uom_id_1') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-2">
                    <label for="cati_id" class="form-label">Category</label>
                    <select class="form-select @error('cati_id') is-invalid @enderror" id="cati_id" name="cati_id">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->cati_id }}" {{ (old('cati_id', $item->cati_id)) == $cat->cati_id ? 'selected' : '' }}>{{ $cat->cati_code }} - {{ $cat->cati_name }}</option>
                        @endforeach
                    </select>
                    @error('cati_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <hr class="my-4">
            <h6 class="fw-bold text-primary mb-3">Detail Information</h6>
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="itemd_code" class="form-label">Detail Code (Serial No)</label>
                    <input type="text" class="form-control @error('itemd_code') is-invalid @enderror" id="itemd_code" name="itemd_code" value="{{ old('itemd_code', $detail?->itemd_code) }}">
                    @error('itemd_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-2">
                    <label for="itemd_qty" class="form-label">Qty</label>
                    <input type="number" class="form-control @error('itemd_qty') is-invalid @enderror" id="itemd_qty" name="itemd_qty" min="1" value="{{ old('itemd_qty', $detail?->itemd_qty ?? 1) }}">
                    @error('itemd_qty') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-2">
                    <label for="itemd_acquired_date" class="form-label">Acquired Date</label>
                    <input type="date" class="form-control @error('itemd_acquired_date') is-invalid @enderror" id="itemd_acquired_date" name="itemd_acquired_date" value="{{ old('itemd_acquired_date', $detail?->itemd_acquired_date?->format('Y-m-d')) }}">
                    @error('itemd_acquired_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-2">
                    <label for="whsl_id" class="form-label">Warehouse</label>
                    <select class="form-select @error('whsl_id') is-invalid @enderror" id="whsl_id" name="whsl_id">
                        <option value="">Pilih Gudang</option>
                        @foreach($warehouses as $w)
                            <option value="{{ $w->whsl_id }}" {{ (old('whsl_id', $detail?->whsl_id)) == $w->whsl_id ? 'selected' : '' }}>{{ $w->whsl_name }}</option>
                        @endforeach
                    </select>
                    @error('whsl_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label for="itemd_status" class="form-label">Status</label>
                    <select class="form-select @error('itemd_status') is-invalid @enderror" id="itemd_status" name="itemd_status">
                        <option value="0" {{ (old('itemd_status', $detail?->itemd_status ?? '0')) == '0' ? 'selected' : '' }}>Aktif</option>
                        <option value="1" {{ old('itemd_status', $detail?->itemd_status) == '1' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                    @error('itemd_status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="row g-3 mt-2">
                <div class="col-md-3">
                    <label for="branch_id" class="form-label">Branch</label>
                    <select class="form-select @error('branch_id') is-invalid @enderror" id="branch_id" name="branch_id">
                        <option value="">Pilih Branch</option>
                        @foreach($branches as $b)
                            <option value="{{ $b->branch_id }}" {{ (old('branch_id', $detail?->branch_id)) == $b->branch_id ? 'selected' : '' }}>{{ $b->branch_code }} - {{ $b->branch_name }}</option>
                        @endforeach
                    </select>
                    @error('branch_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label for="original_branch_id" class="form-label">Branch Original</label>
                    <select class="form-select @error('original_branch_id') is-invalid @enderror" id="original_branch_id" name="original_branch_id">
                        <option value="">Pilih Branch Original</option>
                        @foreach($branches as $b)
                            <option value="{{ $b->branch_id }}" {{ (old('original_branch_id', $detail?->original_branch_id)) == $b->branch_id ? 'selected' : '' }}>{{ $b->branch_code }} - {{ $b->branch_name }}</option>
                        @endforeach
                    </select>
                    @error('original_branch_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="row g-3 mt-2">
                <div class="col-md-6">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="itemd_is_broken" name="itemd_is_broken" value="1" {{ ($detail?->itemd_is_broken) ? 'checked' : '' }}>
                        <label class="form-check-label" for="itemd_is_broken">Broken</label>
                    </div>
                    <div class="form-check form-switch mt-2">
                        <input class="form-check-input" type="checkbox" role="switch" id="itemd_is_dispossed" name="itemd_is_dispossed" value="1" {{ ($detail?->itemd_is_dispossed) ? 'checked' : '' }}>
                        <label class="form-check-label" for="itemd_is_dispossed">Disposed</label>
                    </div>
                    <div class="form-check form-switch mt-2">
                        <input class="form-check-input" type="checkbox" role="switch" id="itemd_is_wo" name="itemd_is_wo" value="1" {{ ($detail?->itemd_is_wo) ? 'checked' : '' }}>
                        <label class="form-check-label" for="itemd_is_wo">Write Off</label>
                    </div>
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Update</button>
                <a href="{{ route('items.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
