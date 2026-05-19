@extends('layouts.app')

@section('title', 'Dashboard')

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var ctx1 = document.getElementById('warehouseChart');
    if (ctx1) {
        new Chart(ctx1.getContext('2d'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($chartData['by_warehouse']['labels'] ?? []) !!},
                datasets: [{
                    label: 'Items by Warehouse',
                    data: {!! json_encode($chartData['by_warehouse']['data'] ?? []) !!},
                    backgroundColor: '#0d6efd',
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });
    }

    var ctx2 = document.getElementById('kategoriChart');
    if (ctx2) {
        new Chart(ctx2.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($chartData['by_kategori']['labels'] ?? []) !!},
                datasets: [{
                    data: {!! json_encode($chartData['by_kategori']['data'] ?? []) !!},
                    backgroundColor: ['#0d6efd', '#6610f2', '#6f42c1', '#d63384', '#dc3545', '#fd7e14', '#ffc107', '#198754'],
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { position: 'bottom' } }
            }
        });
    }

    var ctx3 = document.getElementById('yearChart');
    if (ctx3) {
        new Chart(ctx3.getContext('2d'), {
            type: 'line',
            data: {
                labels: {!! json_encode($chartData['by_year']['labels'] ?? []) !!},
                datasets: [{
                    label: 'Items Acquired',
                    data: {!! json_encode($chartData['by_year']['data'] ?? []) !!},
                    borderColor: '#198754',
                    backgroundColor: 'rgba(25,135,84,0.1)',
                    fill: true,
                    tension: 0.4,
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });
    }
});
</script>
@endpush

@section('content')
<div class="row g-3 mb-4">
    <div class="col-xl-2 col-md-4 col-6">
        <div class="card border-0 shadow-sm bg-primary text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="card-title mb-0 fs-6 fw-light">Total Items</h6>
                        <h2 class="mt-2 mb-0 fw-bold">{{ number_format($stats['total_items']) }}</h2>
                    </div>
                    <i class="bi bi-box fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="card border-0 shadow-sm bg-success text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="card-title mb-0 fs-6 fw-light">Active</h6>
                        <h2 class="mt-2 mb-0 fw-bold">{{ number_format($stats['active_items']) }}</h2>
                    </div>
                    <i class="bi bi-check-circle fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="card border-0 shadow-sm bg-warning text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="card-title mb-0 fs-6 fw-light">Damaged</h6>
                        <h2 class="mt-2 mb-0 fw-bold">{{ number_format($stats['damaged_items']) }}</h2>
                    </div>
                    <i class="bi bi-exclamation-triangle fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="card border-0 shadow-sm bg-info text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="card-title mb-0 fs-6 fw-light">Sold</h6>
                        <h2 class="mt-2 mb-0 fw-bold">{{ number_format($stats['sold_items']) }}</h2>
                    </div>
                    <i class="bi bi-cart-check fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="card border-0 shadow-sm bg-secondary text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="card-title mb-0 fs-6 fw-light">Warehouses</h6>
                        <h2 class="mt-2 mb-0 fw-bold">{{ number_format($stats['total_warehouses']) }}</h2>
                    </div>
                    <i class="bi bi-building fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="card border-0 shadow-sm bg-danger text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="card-title mb-0 fs-6 fw-light">Users</h6>
                        <h2 class="mt-2 mb-0 fw-bold">{{ number_format($stats['total_users']) }}</h2>
                    </div>
                    <i class="bi bi-people fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="fw-bold mb-0">Items by Warehouse</h6>
            </div>
            <div class="card-body">
                <canvas id="warehouseChart" height="250"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="fw-bold mb-0">Items by Category</h6>
            </div>
            <div class="card-body">
                <canvas id="kategoriChart" height="250"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="fw-bold mb-0">Yearly Acquisition</h6>
            </div>
            <div class="card-body">
                <canvas id="yearChart" height="250"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="fw-bold mb-0">Recent Activities</h6>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($recentActivities as $log)
                        <div class="list-group-item py-2 px-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <small>{{ $log->description }}</small>
                                <small class="text-muted">{{ $log->created_at->diffForHumans() }}</small>
                            </div>
                            <small class="text-muted">{{ $log->user?->name ?? 'System' }}</small>
                        </div>
                    @empty
                        <div class="list-group-item text-center text-muted py-4">No recent activities</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
