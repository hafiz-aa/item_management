@extends('layouts.app')

@section('title', 'Edit Kantor Cabang')

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

    $('.btn-delete-child').on('click', function(e) {
        e.preventDefault();
        var form = $(this).closest('form');
        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: 'Apakah Anda yakin ingin menghapus cabang ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) form.submit();
        });
    });
});
</script>
@endpush

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="fw-bold mb-0">Edit: {{ $warehouse->kode_gudang }} - {{ $warehouse->nama_gudang }}</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('warehouses.update', $warehouse) }}">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="kode_gudang" class="form-label">Kode Kantor Cabang <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('kode_gudang') is-invalid @enderror" id="kode_gudang" name="kode_gudang" value="{{ old('kode_gudang', $warehouse->kode_gudang) }}" required>
                    @error('kode_gudang') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label for="nama_gudang" class="form-label">Nama Kantor Cabang <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('nama_gudang') is-invalid @enderror" id="nama_gudang" name="nama_gudang" value="{{ old('nama_gudang', $warehouse->nama_gudang) }}" required>
                    @error('nama_gudang') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="row g-3 mt-2">
                <div class="col-md-6">
                    <label for="tipe" class="form-label">Tipe <span class="text-danger">*</span></label>
                    <select class="form-select @error('tipe') is-invalid @enderror" id="tipe" name="tipe" required>
                        <option value="Kantor Pusat" {{ old('tipe', $warehouse->tipe) == 'Kantor Pusat' ? 'selected' : '' }}>Kantor Pusat</option>
                        <option value="Kantor Cabang" {{ old('tipe', $warehouse->tipe) == 'Kantor Cabang' ? 'selected' : '' }}>Kantor Cabang</option>
                    </select>
                    @error('tipe') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6" id="parent-row">
                    <label for="parent_id" class="form-label">Induk</label>
                    <select class="form-select @error('parent_id') is-invalid @enderror" id="parent_id" name="parent_id">
                        <option value="">-- Pilih Induk --</option>
                        @foreach($parentWarehouses as $p)
                        <option value="{{ $p->warehouse_id }}" {{ old('parent_id', $warehouse->parent_id) == $p->warehouse_id ? 'selected' : '' }}>{{ $p->kode_gudang }} - {{ $p->nama_gudang }}</option>
                        @endforeach
                    </select>
                    @error('parent_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="row g-3 mt-2">
                <div class="col-md-6">
                    <label for="branch_id" class="form-label">Branch</label>
                    <select class="form-select @error('branch_id') is-invalid @enderror" id="branch_id" name="branch_id">
                        <option value="">-- Pilih Branch --</option>
                        @foreach($branches as $b)
                        <option value="{{ $b->branch_id }}" {{ old('branch_id', $warehouse->branch_id) == $b->branch_id ? 'selected' : '' }}>{{ $b->branch_code }} - {{ $b->branch_name }}</option>
                        @endforeach
                    </select>
                    @error('branch_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                        <option value="Aktif" {{ (old('status', $warehouse->status)) == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="Tidak Aktif" {{ old('status', $warehouse->status) == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="mt-3">
                <label for="alamat" class="form-label">Alamat</label>
                <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="3">{{ old('alamat', $warehouse->alamat) }}</textarea>
                @error('alamat') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Update</button>
                <a href="{{ route('warehouses.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm mt-4">
    <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
        <h5 class="fw-bold mb-0">Data Cabang / Branch</h5>
        @can('warehouse.manage')
        <a href="{{ route('warehouses.create', ['parent_id' => $warehouse->warehouse_id]) }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> Tambah Cabang</a>
        @endcan
    </div>
    <div class="card-body">
        @if($warehouse->children->isEmpty())
        <p class="text-muted mb-0">Belum ada cabang.</p>
        @else
        <div class="table-responsive">
            <table class="table table-sm table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Branch</th>
                        <th>Tipe</th>
                        <th>Status</th>
                        <th style="width:130px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($warehouse->children as $child)
                    <tr>
                        <td class="fw-medium">{{ $child->kode_gudang }}</td>
                        <td>{{ $child->nama_gudang }}</td>
                        <td>
                            @if($child->branch)
                                <span class="badge bg-info">{{ $child->branch->branch_code }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td><span class="badge bg-info">{{ $child->tipe }}</span></td>
                        <td><span class="badge bg-{{ $child->status == 'Aktif' ? 'success' : 'secondary' }}">{{ $child->status }}</span></td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('warehouses.edit', $child) }}" class="btn btn-sm btn-warning text-white" title="Edit"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('warehouses.destroy', $child) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger btn-delete-child" title="Delete"><i class="bi bi-trash"></i></button>
                                </form>
                                <a href="{{ route('warehouses.create', ['parent_id' => $child->warehouse_id]) }}" class="btn btn-sm btn-success text-white" title="Add Sub"><i class="bi bi-plus"></i></a>
                            </div>
                        </td>
                    </tr>
                    @if($child->children->isNotEmpty())
                    @foreach($child->children as $sub)
                    <tr>
                        <td class="ps-4 text-muted">{{ $sub->kode_gudang }}</td>
                        <td class="ps-4 text-muted">{{ $sub->nama_gudang }}</td>
                        <td>
                            @if($sub->branch)
                                <span class="badge bg-info">{{ $sub->branch->branch_code }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td><span class="badge bg-secondary">{{ $sub->tipe }}</span></td>
                        <td><span class="badge bg-{{ $sub->status == 'Aktif' ? 'success' : 'secondary' }}">{{ $sub->status }}</span></td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('warehouses.edit', $sub) }}" class="btn btn-sm btn-warning text-white" title="Edit"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('warehouses.destroy', $sub) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger btn-delete-child" title="Delete"><i class="bi bi-trash"></i></button>
                                </form>
                                <a href="{{ route('warehouses.create', ['parent_id' => $sub->warehouse_id]) }}" class="btn btn-sm btn-success text-white" title="Add Sub"><i class="bi bi-plus"></i></a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>
@endsection
