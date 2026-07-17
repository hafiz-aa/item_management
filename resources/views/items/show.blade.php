@extends('layouts.app')

@section('title', 'Item Detail')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0">Item Detail: {{ $item->masti_code }}</h5>
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
                            <td class="fw-bold">{{ $item->masti_code }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Item Name</th>
                            <td>{{ $item->masti_name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Category</th>
                            <td><span class="badge bg-info">{{ $item->category->cati_name ?? '-' }}</span></td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <th style="width:180px" class="text-muted">Capacity</th>
                            <td>{{ number_format($item->masti_capacity, 2) }} {{ $item->uom?->uom_name ?? $item->uom_id_1 }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">UoM</th>
                            <td>{{ $item->uom?->uom_name ?? '-' }}</td>
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
                                        <td>{{ $detail->itemd_qty }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">Status</th>
                                        <td><span class="badge bg-{{ $detail->itemd_status == '0' ? 'success' : 'secondary' }}">{{ $detail->itemd_status == '0' ? 'Aktif' : 'Tidak Aktif' }}</span></td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">Acquired Date</th>
                                        <td>{{ $detail->itemd_acquired_date?->format('d/m/Y') ?? '-' }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <th style="width:180px" class="text-muted">Warehouse</th>
                                        <td>{{ $detail->warehouse?->whsl_name ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">Broken</th>
                                        <td>{!! $detail->itemd_is_broken ? '<span class="badge bg-danger">Ya</span>' : '<span class="badge bg-success">Tidak</span>' !!}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">Disposed</th>
                                        <td>{!! $detail->itemd_is_dispossed ? '<span class="badge bg-warning text-dark">Ya</span>' : '<span class="badge bg-success">Tidak</span>' !!}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">Write Off</th>
                                        <td>{!! $detail->itemd_is_wo ? '<span class="badge bg-danger">Ya</span>' : '<span class="badge bg-success">Tidak</span>' !!}</td>
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
                                        <th class="text-muted">Position</th>
                                        <td>{{ $detail->itemd_position ?? '-' }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <th style="width:180px" class="text-muted">Branch Original</th>
                                        <td>{{ $detail->originalBranch?->branch_code ? $detail->originalBranch->branch_code . ' - ' . $detail->originalBranch->branch_name : '-' }}</td>
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
            <div class="mt-3">
                <a href="{{ route('items.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Back to List</a>
            </div>
        </div>
    </div>
@endsection
