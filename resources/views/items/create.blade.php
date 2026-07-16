@extends('layouts.app')

@section('title', 'Create Item')

@push('scripts')
<script>
$(document).ready(function() {
    $('#branch_id').on('change', function() {
        var $orig = $('#original_branch_id');
        if (!$orig.val()) {
            $orig.val($(this).val());
        }
    });
});
</script>
@endpush

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="fw-bold mb-0">Create New Item</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('items.store') }}">
            @csrf

            <h6 class="fw-bold text-primary mb-3">Header Information</h6>
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="item_code" class="form-label">Item Code <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('item_code') is-invalid @enderror" id="item_code" name="item_code" value="{{ old('item_code') }}" required>
                    @error('item_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label for="item_name" class="form-label">Item Name</label>
                    <input type="text" class="form-control @error('item_name') is-invalid @enderror" id="item_name" name="item_name" value="{{ old('item_name') }}">
                    @error('item_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-2">
                    <label for="capacity" class="form-label">Capacity</label>
                    <input type="number" step="0.01" class="form-control @error('capacity') is-invalid @enderror" id="capacity" name="capacity" value="{{ old('capacity', '0') }}">
                    @error('capacity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-2">
                    <label for="uom_id_1" class="form-label">UoM 1</label>
                    <select class="form-select @error('uom_id_1') is-invalid @enderror" id="uom_id_1" name="uom_id_1">
                        <option value="Kg" {{ old('uom_id_1') == 'Kg' ? 'selected' : '' }}>Kg</option>
                        <option value="Gram" {{ old('uom_id_1') == 'Gram' ? 'selected' : '' }}>Gram</option>
                        <option value="Liter" {{ old('uom_id_1') == 'Liter' ? 'selected' : '' }}>Liter</option>
                        <option value="M3" {{ old('uom_id_1') == 'M3' ? 'selected' : '' }}>M3</option>
                    </select>
                    @error('uom_id_1') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-2">
                    <label for="uom_id_2" class="form-label">UoM 2</label>
                    <input type="text" class="form-control @error('uom_id_2') is-invalid @enderror" id="uom_id_2" name="uom_id_2" value="{{ old('uom_id_2') }}">
                    @error('uom_id_2') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="row g-3 mt-2">
                <div class="col-md-3">
                    <label for="cat_id" class="form-label">Category</label>
                    <input type="text" class="form-control @error('cat_id') is-invalid @enderror" id="cat_id" name="cat_id" value="{{ old('cat_id') }}" list="catList">
                    <datalist id="catList">
                        <option value="Oksigen"><option value="Nitrogen"><option value="Asetilen"><option value="CO2"><option value="LPG"><option value="Hidrogen">
                    </datalist>
                    @error('cat_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label for="company_id" class="form-label">Company ID</label>
                    <input type="number" class="form-control @error('company_id') is-invalid @enderror" id="company_id" name="company_id" value="{{ old('company_id') }}">
                    @error('company_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <hr class="my-4">
            <h6 class="fw-bold text-primary mb-3">Detail Information</h6>
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="itemd_code" class="form-label">Detail Code (Serial No)</label>
                    <input type="text" class="form-control @error('itemd_code') is-invalid @enderror" id="itemd_code" name="itemd_code" value="{{ old('itemd_code') }}">
                    @error('itemd_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-2">
                    <label for="qty" class="form-label">Qty</label>
                    <input type="number" class="form-control @error('qty') is-invalid @enderror" id="qty" name="qty" min="1" value="{{ old('qty', '1') }}">
                    @error('qty') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-2">
                    <label for="acquired_date" class="form-label">Acquired Date</label>
                    <input type="date" class="form-control @error('acquired_date') is-invalid @enderror" id="acquired_date" name="acquired_date" value="{{ old('acquired_date') }}">
                    @error('acquired_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-2">
                    <label for="warehouse_id" class="form-label">Warehouse</label>
                    <select class="form-select @error('warehouse_id') is-invalid @enderror" id="warehouse_id" name="warehouse_id">
                        <option value="">Pilih Gudang</option>
                        @foreach($warehouses as $w)
                            <option value="{{ $w->warehouse_id }}" {{ old('warehouse_id') == $w->warehouse_id ? 'selected' : '' }}>{{ $w->nama_gudang }}</option>
                        @endforeach
                    </select>
                    @error('warehouse_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                        <option value="Aktif" {{ old('status', 'Aktif') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="Tidak Aktif" {{ old('status') == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="row g-3 mt-2">
                <div class="col-md-3">
                    <label for="branch_id" class="form-label">Branch</label>
                    <select class="form-select @error('branch_id') is-invalid @enderror" id="branch_id" name="branch_id">
                        <option value="">Pilih Branch</option>
                        @foreach($branches as $b)
                            <option value="{{ $b->branch_id }}" {{ old('branch_id') == $b->branch_id ? 'selected' : '' }}>{{ $b->branch_code }} - {{ $b->branch_name }}</option>
                        @endforeach
                    </select>
                    @error('branch_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label for="position_id" class="form-label">Position ID</label>
                    <input type="number" class="form-control @error('position_id') is-invalid @enderror" id="position_id" name="position_id" value="{{ old('position_id') }}">
                    @error('position_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label for="original_branch_id" class="form-label">Branch Original</label>
                    <select class="form-select @error('original_branch_id') is-invalid @enderror" id="original_branch_id" name="original_branch_id">
                        <option value="">Pilih Branch Original</option>
                        @foreach($branches as $b)
                            <option value="{{ $b->branch_id }}" {{ old('original_branch_id') == $b->branch_id ? 'selected' : '' }}>{{ $b->branch_code }} - {{ $b->branch_name }}</option>
                        @endforeach
                    </select>
                    @error('original_branch_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="row g-3 mt-2">
                <div class="col-md-6">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="is_broken" name="is_broken" value="1" {{ old('is_broken') ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_broken">Broken</label>
                    </div>
                    <div class="form-check form-switch mt-2">
                        <input class="form-check-input" type="checkbox" role="switch" id="is_dispossed" name="is_dispossed" value="1" {{ old('is_dispossed') ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_dispossed">Disposed</label>
                    </div>
                    <div class="form-check form-switch mt-2">
                        <input class="form-check-input" type="checkbox" role="switch" id="is_writeoff" name="is_writeoff" value="1" {{ old('is_writeoff') ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_writeoff">Write Off</label>
                    </div>
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Save</button>
                <a href="{{ route('items.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
