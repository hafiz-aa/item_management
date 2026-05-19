@extends('layouts.app')

@section('title', 'Item Detail')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0">Item Detail: {{ $item->kode_tabung }}</h5>
        <div>
            @can('item.edit')
            <a href="{{ route('items.edit', $item) }}" class="btn btn-warning btn-sm text-white"><i class="bi bi-pencil"></i> Edit</a>
            @endcan
            <a href="{{ route('items.index') }}" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-left"></i> Back</a>
        </div>
    </div>
    <div class="card-body">
        <div class="row g-4">
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr><th style="width:180px" class="text-muted">Kode Tabung</th><td class="fw-bold">{{ $item->kode_tabung }}</td></tr>
                    <tr><th class="text-muted">Serial No</th><td>{{ $item->serial_no ?? '-' }}</td></tr>
                    <tr><th class="text-muted">Kategori</th><td><span class="badge bg-info">{{ $item->kategori ?? '-' }}</span></td></tr>
                    <tr><th class="text-muted">Status</th><td><span class="badge bg-{{ $item->status == 'Aktif' ? 'success' : 'secondary' }}">{{ $item->status }}</span></td></tr>
                    <tr><th class="text-muted">Rusak</th><td>{!! $item->rusak ? '<span class="badge bg-danger">Ya</span>' : '<span class="badge bg-success">Tidak</span>' !!}</td></tr>
                    <tr><th class="text-muted">Dijual</th><td>{!! $item->dijual ? '<span class="badge bg-warning text-dark">Ya</span>' : '<span class="badge bg-success">Tidak</span>' !!}</td></tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr><th style="width:180px" class="text-muted">Deskripsi</th><td>{{ $item->deskripsi_isi_tabung ?? '-' }}</td></tr>
                    <tr><th class="text-muted">Tahun Pembuatan</th><td>{{ $item->tahun_pembuatan ?? '-' }}</td></tr>
                    <tr><th class="text-muted">Berat / Kapasitas</th><td>{{ number_format($item->berat, 2) }} / {{ number_format($item->kapasitas, 2) }} {{ $item->uom }}</td></tr>
                    <tr><th class="text-muted">Qty</th><td>{{ $item->qty }}</td></tr>
                    <tr><th class="text-muted">Tanggal Perolehan</th><td>{{ $item->tanggal_perolehan?->format('d/m/Y') ?? '-' }}</td></tr>
                    <tr><th class="text-muted">Lokasi Gudang</th><td>{{ $item->warehouse?->nama_gudang ?? '-' }}</td></tr>
                </table>
            </div>
        </div>

        <hr>
        <div class="row g-3">
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr><th style="width:180px" class="text-muted">Vendor</th><td>{{ $item->vendor ?? '-' }}</td></tr>
                    <tr><th class="text-muted">Pemilik Tabung</th><td>{{ $item->pemilik_tabung ?? '-' }}</td></tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr><th style="width:180px" class="text-muted">Dibuat Oleh</th><td>{{ $item->creator?->name ?? 'System' }} - {{ $item->created_at?->format('d/m/Y H:i') }}</td></tr>
                    <tr><th class="text-muted">Diupdate Oleh</th><td>{{ $item->updater?->name ?? '-' }} - {{ $item->updated_at?->format('d/m/Y H:i') }}</td></tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
