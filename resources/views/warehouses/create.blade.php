@extends('layouts.app')

@section('title', 'Tambah Data Kantor Cabang Baru')

@push('scripts')
<script>
$(document).ready(function() {
    function toggleParent() {
        if ($('#whsl_type').val() === '1') {
            $('#parent-row').show();
        } else {
            $('#parent-row').hide();
            $('#whsl_parent_id').val('');
        }
    }
    toggleParent();
    $('#whsl_type').on('change', toggleParent);
});
</script>
@endpush

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="fw-bold mb-0">TAMBAH DATA KANTOR CABANG BARU</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('warehouses.store') }}">
            @csrf
            @if($selectedParent)
            <input type="hidden" name="whsl_parent_id" value="{{ $selectedParent->whsl_id }}">
            <input type="hidden" name="whsl_type" value="1">
            @endif
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="whsl_code" class="form-label">Kode Kantor Cabang <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('whsl_code') is-invalid @enderror" id="whsl_code" name="whsl_code" value="{{ old('whsl_code') }}" required>
                    @error('whsl_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label for="whsl_name" class="form-label">Nama Kantor Cabang <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('whsl_name') is-invalid @enderror" id="whsl_name" name="whsl_name" value="{{ old('whsl_name') }}" required>
                    @error('whsl_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            @if(!$selectedParent)
            <div class="row g-3 mt-2">
                <div class="col-md-6">
                    <label for="whsl_type" class="form-label">Tipe <span class="text-danger">*</span></label>
                    <select class="form-select @error('whsl_type') is-invalid @enderror" id="whsl_type" name="whsl_type" required>
                        <option value="">-- Pilih Tipe --</option>
                        <option value="0" {{ old('whsl_type') == '0' ? 'selected' : '' }}>Kantor Pusat</option>
                        <option value="1" {{ old('whsl_type') == '1' ? 'selected' : '' }}>Kantor Cabang</option>
                    </select>
                    @error('whsl_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6" id="parent-row" style="display:none">
                    <label for="whsl_parent_id" class="form-label">Induk</label>
                    <select class="form-select @error('whsl_parent_id') is-invalid @enderror" id="whsl_parent_id" name="whsl_parent_id">
                        <option value="">-- Pilih Induk --</option>
                        @foreach($parentWarehouses as $p)
                        <option value="{{ $p->whsl_id }}" {{ old('whsl_parent_id') == $p->whsl_id ? 'selected' : '' }}>{{ $p->whsl_code }} - {{ $p->whsl_name }}</option>
                        @endforeach
                    </select>
                    @error('whsl_parent_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            @else
            <div class="row g-3 mt-2">
                <div class="col-md-6">
                    <label class="form-label">Induk</label>
                    <input type="text" class="form-control" value="{{ $selectedParent->whsl_code }} - {{ $selectedParent->whsl_name }}" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tipe</label>
                    <input type="text" class="form-control" value="Kantor Cabang" readonly>
                </div>
            </div>
            @endif
            <div class="row g-3 mt-2">
                <div class="col-md-6">
                    <label for="branch_id" class="form-label">Branch</label>
                    <select class="form-select @error('branch_id') is-invalid @enderror" id="branch_id" name="branch_id">
                        <option value="">-- Pilih Branch --</option>
                        @foreach($branches as $b)
                        <option value="{{ $b->branch_id }}" {{ old('branch_id') == $b->branch_id ? 'selected' : '' }}>{{ $b->branch_code }} - {{ $b->branch_name }}</option>
                        @endforeach
                    </select>
                    @error('branch_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label for="whsl_status" class="form-label">Status</label>
                    <select class="form-select @error('whsl_status') is-invalid @enderror" id="whsl_status" name="whsl_status">
                        <option value="0" {{ old('whsl_status', '0') == '0' ? 'selected' : '' }}>Aktif</option>
                        <option value="1" {{ old('whsl_status') == '1' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                    @error('whsl_status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Save</button>
                <a href="{{ route('warehouses.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
