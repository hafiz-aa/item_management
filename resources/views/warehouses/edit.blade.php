@extends('layouts.app')

@section('title', 'Edit Kantor Cabang')

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        <h5 class="fw-bold mb-0">Edit: {{ $warehouse->whsl_code }} - {{ $warehouse->whsl_name }}</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('warehouses.update', $warehouse) }}">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="whsl_code" class="form-label">Kode Kantor Cabang <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('whsl_code') is-invalid @enderror" id="whsl_code" name="whsl_code" value="{{ old('whsl_code', $warehouse->whsl_code) }}" required>
                    @error('whsl_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label for="whsl_name" class="form-label">Nama Kantor Cabang <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('whsl_name') is-invalid @enderror" id="whsl_name" name="whsl_name" value="{{ old('whsl_name', $warehouse->whsl_name) }}" required>
                    @error('whsl_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="row g-3 mt-2">
                <div class="col-md-6">
                    <label for="whsl_type" class="form-label">Tipe <span class="text-danger">*</span></label>
                    <select class="form-select @error('whsl_type') is-invalid @enderror" id="whsl_type" name="whsl_type" required>
                        <option value="0" {{ old('whsl_type', $warehouse->whsl_type) == '0' ? 'selected' : '' }}>Kantor Pusat</option>
                        <option value="1" {{ old('whsl_type', $warehouse->whsl_type) == '1' ? 'selected' : '' }}>Kantor Cabang</option>
                    </select>
                    @error('whsl_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6" id="parent-row">
                    <label for="whsl_parent_id" class="form-label">Induk</label>
                    <select class="form-select @error('whsl_parent_id') is-invalid @enderror" id="whsl_parent_id" name="whsl_parent_id">
                        <option value="">-- Pilih Induk --</option>
                        @foreach($parentWarehouses as $p)
                        <option value="{{ $p->whsl_id }}" {{ old('whsl_parent_id', $warehouse->whsl_parent_id) == $p->whsl_id ? 'selected' : '' }}>{{ $p->whsl_code }} - {{ $p->whsl_name }}</option>
                        @endforeach
                    </select>
                    @error('whsl_parent_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
                    <label for="whsl_status" class="form-label">Status</label>
                    <select class="form-select @error('whsl_status') is-invalid @enderror" id="whsl_status" name="whsl_status">
                        <option value="0" {{ (old('whsl_status', $warehouse->whsl_status)) == '0' ? 'selected' : '' }}>Aktif</option>
                        <option value="1" {{ old('whsl_status', $warehouse->whsl_status) == '1' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                    @error('whsl_status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
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
        <a href="{{ route('warehouses.create', ['whsl_parent_id' => $warehouse->whsl_id]) }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> Tambah Cabang</a>
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
                        <td class="fw-medium">{{ $child->whsl_code }}</td>
                        <td>{{ $child->whsl_name }}</td>
                        <td>
                            @if($child->branch)
                                <span class="badge bg-info">{{ $child->branch->branch_code }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td><span class="badge bg-info">{{ $child->whsl_type == '0' ? 'Kantor Pusat' : 'Kantor Cabang' }}</span></td>
                        <td><span class="badge bg-{{ $child->whsl_status == '0' ? 'success' : 'secondary' }}">{{ $child->whsl_status == '0' ? 'Aktif' : 'Tidak Aktif' }}</span></td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('warehouses.edit', $child) }}" class="btn btn-sm btn-warning text-white" title="Edit"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('warehouses.destroy', $child) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger btn-delete-child" title="Delete"><i class="bi bi-trash"></i></button>
                                </form>
                                <a href="{{ route('warehouses.create', ['whsl_parent_id' => $child->whsl_id]) }}" class="btn btn-sm btn-success text-white" title="Add Sub"><i class="bi bi-plus"></i></a>
                            </div>
                        </td>
                    </tr>
                    @if($child->children->isNotEmpty())
                    @foreach($child->children as $sub)
                    <tr>
                        <td class="ps-4 text-muted">{{ $sub->whsl_code }}</td>
                        <td class="ps-4 text-muted">{{ $sub->whsl_name }}</td>
                        <td>
                            @if($sub->branch)
                                <span class="badge bg-info">{{ $sub->branch->branch_code }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td><span class="badge bg-secondary">{{ $sub->whsl_type == '0' ? 'Kantor Pusat' : 'Kantor Cabang' }}</span></td>
                        <td><span class="badge bg-{{ $sub->whsl_status == '0' ? 'success' : 'secondary' }}">{{ $sub->whsl_status == '0' ? 'Aktif' : 'Tidak Aktif' }}</span></td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('warehouses.edit', $sub) }}" class="btn btn-sm btn-warning text-white" title="Edit"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('warehouses.destroy', $sub) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger btn-delete-child" title="Delete"><i class="bi bi-trash"></i></button>
                                </form>
                                <a href="{{ route('warehouses.create', ['whsl_parent_id' => $sub->whsl_id]) }}" class="btn btn-sm btn-success text-white" title="Add Sub"><i class="bi bi-plus"></i></a>
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
