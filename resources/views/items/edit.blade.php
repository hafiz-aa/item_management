@extends('layouts.app')

@section('title', 'Edit Item')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="fw-bold mb-0">Edit Item / Tabung: {{ $item->kode_tabung }}</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('items.update', $item) }}">
            @csrf @method('PUT')

            <div class="row g-3">
                <div class="col-md-4">
                    <label for="kode_tabung" class="form-label">Kode Tabung <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('kode_tabung') is-invalid @enderror" id="kode_tabung" name="kode_tabung" value="{{ old('kode_tabung', $item->kode_tabung) }}" required>
                    @error('kode_tabung') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label for="serial_no" class="form-label">Serial No</label>
                    <input type="text" class="form-control @error('serial_no') is-invalid @enderror" id="serial_no" name="serial_no" value="{{ old('serial_no', $item->serial_no) }}">
                    @error('serial_no') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label for="kategori" class="form-label">Kategori</label>
                    <input type="text" class="form-control @error('kategori') is-invalid @enderror" id="kategori" name="kategori" value="{{ old('kategori', $item->kategori) }}" list="kategoriList">
                    <datalist id="kategoriList">
                        <option value="Oksigen"><option value="Nitrogen"><option value="Asetilen"><option value="CO2"><option value="LPG"><option value="Hidrogen">
                    </datalist>
                    @error('kategori') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="row g-3 mt-2">
                <div class="col-md-6">
                    <label for="deskripsi_isi_tabung" class="form-label">Deskripsi Isi Tabung</label>
                    <textarea class="form-control @error('deskripsi_isi_tabung') is-invalid @enderror" id="deskripsi_isi_tabung" name="deskripsi_isi_tabung" rows="3">{{ old('deskripsi_isi_tabung', $item->deskripsi_isi_tabung) }}</textarea>
                    @error('deskripsi_isi_tabung') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label for="vendor" class="form-label">Vendor</label>
                    <input type="text" class="form-control @error('vendor') is-invalid @enderror" id="vendor" name="vendor" value="{{ old('vendor', $item->vendor) }}">
                    @error('vendor') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label for="pemilik_tabung" class="form-label">Pemilik Tabung</label>
                    <input type="text" class="form-control @error('pemilik_tabung') is-invalid @enderror" id="pemilik_tabung" name="pemilik_tabung" value="{{ old('pemilik_tabung', $item->pemilik_tabung) }}">
                    @error('pemilik_tabung') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="row g-3 mt-2">
                <div class="col-md-3">
                    <label for="tahun_pembuatan" class="form-label">Tahun Pembuatan</label>
                    <input type="number" class="form-control @error('tahun_pembuatan') is-invalid @enderror" id="tahun_pembuatan" name="tahun_pembuatan" min="1900" max="{{ date('Y') }}" value="{{ old('tahun_pembuatan', $item->tahun_pembuatan) }}">
                    @error('tahun_pembuatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label for="berat" class="form-label">Berat</label>
                    <input type="number" step="0.01" class="form-control @error('berat') is-invalid @enderror" id="berat" name="berat" value="{{ old('berat', $item->berat) }}">
                    @error('berat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label for="kapasitas" class="form-label">Kapasitas</label>
                    <input type="number" step="0.01" class="form-control @error('kapasitas') is-invalid @enderror" id="kapasitas" name="kapasitas" value="{{ old('kapasitas', $item->kapasitas) }}">
                    @error('kapasitas') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label for="uom" class="form-label">UoM</label>
                    <select class="form-select @error('uom') is-invalid @enderror" id="uom" name="uom">
                        <option value="Kg" {{ (old('uom', $item->uom)) == 'Kg' ? 'selected' : '' }}>Kg</option>
                        <option value="Gram" {{ old('uom', $item->uom) == 'Gram' ? 'selected' : '' }}>Gram</option>
                        <option value="Liter" {{ old('uom', $item->uom) == 'Liter' ? 'selected' : '' }}>Liter</option>
                        <option value="M3" {{ old('uom', $item->uom) == 'M3' ? 'selected' : '' }}>M3</option>
                    </select>
                    @error('uom') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="row g-3 mt-2">
                <div class="col-md-3">
                    <label for="qty" class="form-label">Qty</label>
                    <input type="number" class="form-control @error('qty') is-invalid @enderror" id="qty" name="qty" min="1" value="{{ old('qty', $item->qty) }}">
                    @error('qty') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label for="tanggal_perolehan" class="form-label">Tanggal Perolehan</label>
                    <input type="date" class="form-control @error('tanggal_perolehan') is-invalid @enderror" id="tanggal_perolehan" name="tanggal_perolehan" value="{{ old('tanggal_perolehan', $item->tanggal_perolehan?->format('Y-m-d')) }}">
                    @error('tanggal_perolehan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label for="lokasi_gudang_id" class="form-label">Lokasi Gudang</label>
                    <select class="form-select @error('lokasi_gudang_id') is-invalid @enderror" id="lokasi_gudang_id" name="lokasi_gudang_id">
                        <option value="">Pilih Gudang</option>
                        @foreach($warehouses as $w)
                            <option value="{{ $w->id }}" {{ (old('lokasi_gudang_id', $item->lokasi_gudang_id)) == $w->id ? 'selected' : '' }}>{{ $w->nama_gudang }}</option>
                        @endforeach
                    </select>
                    @error('lokasi_gudang_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                        <option value="Aktif" {{ (old('status', $item->status)) == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="Tidak Aktif" {{ old('status', $item->status) == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="row g-3 mt-2">
                <div class="col-md-6">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="rusak" name="rusak" value="1" {{ $item->rusak ? 'checked' : '' }}>
                        <label class="form-check-label" for="rusak">Rusak</label>
                    </div>
                    <div class="form-check form-switch mt-2">
                        <input class="form-check-input" type="checkbox" role="switch" id="dijual" name="dijual" value="1" {{ $item->dijual ? 'checked' : '' }}>
                        <label class="form-check-label" for="dijual">Dijual</label>
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
