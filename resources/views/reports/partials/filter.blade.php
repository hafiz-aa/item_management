<form method="GET" action="{{ $action }}" class="row g-2 mb-3">
    <div class="col-md-2">
        <input type="date" name="date_from" class="form-control form-control-sm" value="{{ $filters['date_from'] ?? '' }}">
    </div>
    <div class="col-md-2">
        <input type="date" name="date_to" class="form-control form-control-sm" value="{{ $filters['date_to'] ?? '' }}">
    </div>
    @if($searchable ?? true)
        <div class="col-md-2">
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Search..."
                value="{{ $filters['search'] ?? '' }}">
        </div>
    @endif
    {{ $extra ?? '' }}
    <div class="col-md-1">
        <button type="submit" class="btn btn-sm btn-primary w-100"><i class="bi bi-search"></i></button>
    </div>
    <div class="col-md-1">
        <a href="{{ $action }}" class="btn btn-sm btn-secondary w-100"><i class="bi bi-x-lg"></i></a>
    </div>
</form>
