@extends('layouts.app')

@section('title', 'Tambah Data Kantor Cabang Baru')

@push('scripts')
<script>
$(document).ready(function() {
    function toggleParent() {
        if ($('#tipe').val() === 'Kantor Cabang') {
            $('#parent-row').show();
        } else {
            $('#parent-row').hide();
            $('#parent_id').val('');
        }
    }
    toggleParent();
    $('#tipe').on('change', toggleParent);
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
            <input type="hidden" name="parent_id" value="{{ $selectedParent->warehouse_id }}">
            <input type="hidden" name="tipe" value="Kantor Cabang">
            @endif
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="kode_gudang" class="form-label">Kode Kantor Cabang <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('kode_gudang') is-invalid @enderror" id="kode_gudang" name="kode_gudang" value="{{ old('kode_gudang') }}" required>
                    @error('kode_gudang') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label for="nama_gudang" class="form-label">Nama Kantor Cabang <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('nama_gudang') is-invalid @enderror" id="nama_gudang" name="nama_gudang" value="{{ old('nama_gudang') }}" required>
                    @error('nama_gudang') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            @if(!$selectedParent)
            <div class="row g-3 mt-2">
                <div class="col-md-6">
                    <label for="tipe" class="form-label">Tipe <span class="text-danger">*</span></label>
                    <select class="form-select @error('tipe') is-invalid @enderror" id="tipe" name="tipe" required>
                        <option value="">-- Pilih Tipe --</option>
                        <option value="Kantor Pusat" {{ old('tipe') == 'Kantor Pusat' ? 'selected' : '' }}>Kantor Pusat</option>
                        <option value="Kantor Cabang" {{ old('tipe') == 'Kantor Cabang' ? 'selected' : '' }}>Kantor Cabang</option>
                    </select>
                    @error('tipe') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6" id="parent-row" style="display:none">
                    <label for="parent_id" class="form-label">Induk</label>
                    <select class="form-select @error('parent_id') is-invalid @enderror" id="parent_id" name="parent_id">
                        <option value="">-- Pilih Induk --</option>
                        @foreach($parentWarehouses as $p)
                        <option value="{{ $p->warehouse_id }}" {{ old('parent_id') == $p->warehouse_id ? 'selected' : '' }}>{{ $p->kode_gudang }} - {{ $p->nama_gudang }}</option>
                        @endforeach
                    </select>
                    @error('parent_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            @else
            <div class="row g-3 mt-2">
                <div class="col-md-6">
                    <label class="form-label">Induk</label>
                    <input type="text" class="form-control" value="{{ $selectedParent->kode_gudang }} - {{ $selectedParent->nama_gudang }}" readonly>
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
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                        <option value="Aktif" {{ old('status', 'Aktif') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="Tidak Aktif" {{ old('status') == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="mt-3">
                <label for="alamat" class="form-label">Alamat</label>
                <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="3">{{ old('alamat') }}</textarea>
                @error('alamat') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Save</button>
                <a href="{{ route('warehouses.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
