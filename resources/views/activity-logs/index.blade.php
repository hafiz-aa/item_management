@extends('layouts.app')

@section('title', 'Activity Logs')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="fw-bold mb-0">Activity Logs</h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('activity-logs.index') }}" class="row g-2 mb-3">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search description..." value="{{ $filters['search'] ?? '' }}">
            </div>
            <div class="col-md-2">
                <select name="type" class="form-select form-select-sm">
                    <option value="">All Types</option>
                    <option value="item_created" {{ ($filters['type'] ?? '') == 'item_created' ? 'selected' : '' }}>Item Created</option>
                    <option value="item_updated" {{ ($filters['type'] ?? '') == 'item_updated' ? 'selected' : '' }}>Item Updated</option>
                    <option value="item_deleted" {{ ($filters['type'] ?? '') == 'item_deleted' ? 'selected' : '' }}>Item Deleted</option>
                    <option value="items_bulk_deleted" {{ ($filters['type'] ?? '') == 'items_bulk_deleted' ? 'selected' : '' }}>Bulk Delete</option>
                    <option value="warehouse_created" {{ ($filters['type'] ?? '') == 'warehouse_created' ? 'selected' : '' }}>Warehouse Created</option>
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" name="date_from" class="form-control form-control-sm" placeholder="From" value="{{ $filters['date_from'] ?? '' }}">
            </div>
            <div class="col-md-2">
                <input type="date" name="date_to" class="form-control form-control-sm" placeholder="To" value="{{ $filters['date_to'] ?? '' }}">
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-sm btn-primary w-100"><i class="bi bi-search"></i></button>
            </div>
            <div class="col-md-1">
                <a href="{{ route('activity-logs.index') }}" class="btn btn-sm btn-secondary w-100"><i class="bi bi-x-lg"></i></a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover table-striped align-middle">
                <thead class="table-dark">
                    <tr><th>Time</th><th>User</th><th>Type</th><th>Description</th><th>IP Address</th></tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td class="small">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                        <td>{{ $log->user?->name ?? 'System' }}</td>
                        <td><span class="badge bg-info">{{ $log->type }}</span></td>
                        <td>{{ $log->description }}</td>
                        <td class="small text-muted">{{ $log->ip_address ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center py-4 text-muted">No activity logs found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center mt-3">{{ $logs->links() }}</div>
    </div>
</div>
@endsection
