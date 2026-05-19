@extends('layouts.app')

@section('title', 'Edit Warehouse')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="fw-bold mb-0">Edit Warehouse: {{ $warehouse->nama_gudang }}</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('warehouses.update', $warehouse) }}">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="kode_gudang" class="form-label">Kode Gudang <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('kode_gudang') is-invalid @enderror" id="kode_gudang" name="kode_gudang" value="{{ old('kode_gudang', $warehouse->kode_gudang) }}" required>
                    @error('kode_gudang') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label for="nama_gudang" class="form-label">Nama Gudang <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('nama_gudang') is-invalid @enderror" id="nama_gudang" name="nama_gudang" value="{{ old('nama_gudang', $warehouse->nama_gudang) }}" required>
                    @error('nama_gudang') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="row g-3 mt-2">
                <div class="col-md-4">
                    <label for="kota" class="form-label">Kota</label>
                    <input type="text" class="form-control @error('kota') is-invalid @enderror" id="kota" name="kota" value="{{ old('kota', $warehouse->kota) }}">
                </div>
                <div class="col-md-4">
                    <label for="provinsi" class="form-label">Provinsi</label>
                    <input type="text" class="form-control @error('provinsi') is-invalid @enderror" id="provinsi" name="provinsi" value="{{ old('provinsi', $warehouse->provinsi) }}">
                </div>
                <div class="col-md-4">
                    <label for="telepon" class="form-label">Telepon</label>
                    <input type="text" class="form-control @error('telepon') is-invalid @enderror" id="telepon" name="telepon" value="{{ old('telepon', $warehouse->telepon) }}">
                </div>
            </div>
            <div class="row g-3 mt-2">
                <div class="col-md-6">
                    <label for="penanggung_jawab" class="form-label">Penanggung Jawab</label>
                    <input type="text" class="form-control @error('penanggung_jawab') is-invalid @enderror" id="penanggung_jawab" name="penanggung_jawab" value="{{ old('penanggung_jawab', $warehouse->penanggung_jawab) }}">
                </div>
                <div class="col-md-6">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                        <option value="Aktif" {{ (old('status', $warehouse->status)) == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="Tidak Aktif" {{ old('status', $warehouse->status) == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                </div>
            </div>
            <div class="mt-3">
                <label for="alamat" class="form-label">Alamat</label>
                <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="3">{{ old('alamat', $warehouse->alamat) }}</textarea>
            </div>
            <div class="mt-3">
                <label for="keterangan" class="form-label">Keterangan</label>
                <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan" rows="2">{{ old('keterangan', $warehouse->keterangan) }}</textarea>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Update</button>
                <a href="{{ route('warehouses.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
