@extends('layouts.app')

@section('title', 'Warehouses')

@push('styles')
<style>
    .child-row { display: none; }
    .toggle-parent { cursor: pointer; }
    .toggle-parent:hover { filter: brightness(0.95); }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    $('.btn-delete').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        var form = $(this).closest('form');
        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: 'Apakah Anda yakin ingin menghapus data ini?',
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

    $('.btn-edit').on('click', function(e) {
        e.stopPropagation();
    });

    $('.toggle-parent').on('click', function() {
        var id = $(this).data('warehouse-id');
        var children = $('.child-of-' + id);
        var icon = $(this).find('.toggle-icon');

        children.toggle();
        icon.toggleClass('bi-chevron-down bi-chevron-up');
    });
});
</script>
@endpush

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
        <h5 class="fw-bold mb-0">Warehouses / Gudang</h5>
        @can('warehouse.manage')
        <a href="{{ route('warehouses.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> Tambah Data Kantor Cabang Baru</a>
        @endcan
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th style="width:32px"></th>
                        <th>Parent</th>
                        <th>Kode Lokasi Gudang</th>
                        <th>Nama Lokasi Gudang</th>
                        <th>Branch</th>
                        <th>Tipe</th>
                        <th>Alamat</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($warehouses as $w)
                    @php $hasChildren = $w->children->isNotEmpty(); @endphp
                    <tr class="table-primary toggle-parent {{ $hasChildren ? '' : '' }}" data-warehouse-id="{{ $w->warehouse_id }}">
                        <td>@if($hasChildren)<i class="bi bi-chevron-down toggle-icon"></i>@endif</td>
                        <td class="fw-bold">-</td>
                        <td class="fw-medium">{{ $w->kode_gudang }}</td>
                        <td>{{ $w->nama_gudang }}</td>
                        <td>
                            @if($w->branch)
                                <span class="badge bg-info">{{ $w->branch->branch_code }}</span>
                                {{ $w->branch->branch_name }}
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td><span class="badge bg-primary">{{ $w->tipe }}</span></td>
                        <td>{{ $w->alamat ?? '-' }}</td>
                        <td>
                            <span class="badge bg-{{ $w->status == 'Aktif' ? 'success' : 'secondary' }}">{{ $w->status }}</span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                @can('warehouse.manage')
                                <a href="{{ route('warehouses.edit', $w) }}" class="btn btn-sm btn-warning text-white btn-edit" title="Edit"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('warehouses.destroy', $w) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger btn-delete" title="Delete"><i class="bi bi-trash"></i></button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @foreach($w->children as $c1)
                    @php $hasChildren1 = $c1->children->isNotEmpty(); @endphp
                    <tr class="child-of-{{ $w->warehouse_id }} {{ $hasChildren1 ? 'toggle-parent' : '' }}" @if($hasChildren1) data-warehouse-id="{{ $c1->warehouse_id }}" @endif>
                        <td>@if($hasChildren1)<i class="bi bi-chevron-down toggle-icon"></i>@endif</td>
                        <td class="text-muted">{{ $w->nama_gudang }}</td>
                        <td class="ps-4 text-muted">{{ $c1->kode_gudang }}</td>
                        <td class="ps-4 text-muted">{{ $c1->nama_gudang }}</td>
                        <td>
                            @if($c1->branch)
                                <span class="badge bg-info">{{ $c1->branch->branch_code }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td><span class="badge bg-info">{{ $c1->tipe }}</span></td>
                        <td>-</td>
                        <td>
                            <span class="badge bg-{{ $c1->status == 'Aktif' ? 'success' : 'secondary' }}">{{ $c1->status }}</span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                @can('warehouse.manage')
                                <a href="{{ route('warehouses.edit', $c1) }}" class="btn btn-sm btn-warning text-white btn-edit" title="Edit"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('warehouses.destroy', $c1) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger btn-delete" title="Delete"><i class="bi bi-trash"></i></button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @foreach($c1->children as $c2)
                    @php $hasChildren2 = $c2->children->isNotEmpty(); @endphp
                    <tr class="child-of-{{ $w->warehouse_id }} child-of-{{ $c1->warehouse_id }} {{ $hasChildren2 ? 'toggle-parent' : '' }}" @if($hasChildren2) data-warehouse-id="{{ $c2->warehouse_id }}" @endif>
                        <td>@if($hasChildren2)<i class="bi bi-chevron-down toggle-icon"></i>@endif</td>
                        <td class="text-muted">{{ $c1->nama_gudang }}</td>
                        <td class="ps-5 text-muted">{{ $c2->kode_gudang }}</td>
                        <td class="ps-5 text-muted">{{ $c2->nama_gudang }}</td>
                        <td>
                            @if($c2->branch)
                                <span class="badge bg-info">{{ $c2->branch->branch_code }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td><span class="badge bg-secondary">{{ $c2->tipe }}</span></td>
                        <td>-</td>
                        <td>
                            <span class="badge bg-{{ $c2->status == 'Aktif' ? 'success' : 'secondary' }}">{{ $c2->status }}</span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                @can('warehouse.manage')
                                <a href="{{ route('warehouses.edit', $c2) }}" class="btn btn-sm btn-warning text-white btn-edit" title="Edit"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('warehouses.destroy', $c2) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger btn-delete" title="Delete"><i class="bi bi-trash"></i></button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @foreach($c2->children as $c3)
                    <tr class="child-of-{{ $w->warehouse_id }} child-of-{{ $c1->warehouse_id }} child-of-{{ $c2->warehouse_id }}">
                        <td></td>
                        <td class="text-muted">{{ $c2->nama_gudang }}</td>
                        <td class="ps-6 text-muted">{{ $c3->kode_gudang }}</td>
                        <td class="ps-6 text-muted">{{ $c3->nama_gudang }}</td>
                        <td>
                            @if($c3->branch)
                                <span class="badge bg-info">{{ $c3->branch->branch_code }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td><span class="badge bg-secondary">{{ $c3->tipe }}</span></td>
                        <td>-</td>
                        <td>
                            <span class="badge bg-{{ $c3->status == 'Aktif' ? 'success' : 'secondary' }}">{{ $c3->status }}</span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                @can('warehouse.manage')
                                <a href="{{ route('warehouses.edit', $c3) }}" class="btn btn-sm btn-warning text-white btn-edit" title="Edit"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('warehouses.destroy', $c3) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger btn-delete" title="Delete"><i class="bi bi-trash"></i></button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    @endforeach
                    @endforeach
                    @empty
                    <tr><td colspan="9" class="text-center py-4 text-muted">No data found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
