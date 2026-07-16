@extends('layouts.app')

@section('title', 'Item Detail')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0">Item Detail: {{ $item->item_code }}</h5>
            <div>
                @can('item.edit')
                    <a href="{{ route('items.edit', $item) }}" class="btn btn-warning btn-sm text-white"><i
                            class="bi bi-pencil"></i> Edit</a>
                @endcan
                <a href="{{ route('items.index') }}" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-left"></i> Back</a>
            </div>
        </div>
        <div class="card-body">
            <h6 class="fw-bold text-primary mb-3">Header Information</h6>
            <div class="row g-4">
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <th style="width:180px" class="text-muted">Item Code</th>
                            <td class="fw-bold">{{ $item->item_code }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Item Name</th>
                            <td>{{ $item->item_name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Category</th>
                            <td><span class="badge bg-info">{{ $item->cat_id ?? '-' }}</span></td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <th style="width:180px" class="text-muted">Capacity</th>
                            <td>{{ number_format($item->capacity, 2) }} {{ $item->uom_id_1 }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">UoM 2</th>
                            <td>{{ $item->uom_id_2 ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Company ID</th>
                            <td>{{ $item->company_id ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <hr>
            <h6 class="fw-bold text-primary mb-3">Detail Information</h6>
            @forelse($item->details as $detail)
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <th style="width:180px" class="text-muted">Detail Code</th>
                                        <td class="fw-bold">{{ $detail->itemd_code ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">Qty</th>
                                        <td>{{ $detail->qty }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">Status</th>
                                        <td><span class="badge bg-{{ $detail->status == 'Aktif' ? 'success' : 'secondary' }}">{{ $detail->status }}</span></td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">Acquired Date</th>
                                        <td>{{ $detail->acquired_date?->format('d/m/Y') ?? '-' }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <th style="width:180px" class="text-muted">Warehouse</th>
                                        <td>{{ $detail->warehouse?->nama_gudang ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">Broken</th>
                                        <td>{!! $detail->is_broken ? '<span class="badge bg-danger">Ya</span>' : '<span class="badge bg-success">Tidak</span>' !!}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">Disposed</th>
                                        <td>{!! $detail->is_dispossed ? '<span class="badge bg-warning text-dark">Ya</span>' : '<span class="badge bg-success">Tidak</span>' !!}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">Write Off</th>
                                        <td>{!! $detail->is_writeoff ? '<span class="badge bg-danger">Ya</span>' : '<span class="badge bg-success">Tidak</span>' !!}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="row g-4">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <th style="width:180px" class="text-muted">Branch</th>
                                        <td>{{ $detail->branch?->branch_code ? $detail->branch->branch_code . ' - ' . $detail->branch->branch_name : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">Position ID</th>
                                        <td>{{ $detail->position_id ?? '-' }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <th style="width:180px" class="text-muted">Branch Original</th>
                                        <td>{{ $detail->originalBranch?->branch_code ? $detail->originalBranch->branch_code . ' - ' . $detail->originalBranch->branch_name : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">Dibuat Oleh</th>
                                        <td>{{ $detail->creator?->name ?? 'System' }} - {{ $detail->created_at?->format('d/m/Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">Diupdate Oleh</th>
                                        <td>{{ $detail->updater?->name ?? '-' }} - {{ $detail->updated_at?->format('d/m/Y H:i') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-muted">No detail records found.</p>
            @endforelse

            <hr>
            <div class="row g-3">
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <th style="width:180px" class="text-muted">Dibuat Oleh</th>
                            <td>{{ $item->creator?->name ?? 'System' }} - {{ $item->created_at?->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Diupdate Oleh</th>
                            <td>{{ $item->updater?->name ?? '-' }} - {{ $item->updated_at?->format('d/m/Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
