@extends('layouts.app')

@section('title', 'Import Result')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="fw-bold mb-0">Import Result</h5>
    </div>
    <div class="card-body">
        @if(session('warning'))
            <div class="alert alert-warning">{{ session('warning') }}</div>
        @endif

        @if(session('import_errors'))
            <div class="card border-danger mb-3">
                <div class="card-header bg-danger text-white">Failed Rows</div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr><th>Row</th><th>Column</th><th>Error</th></tr>
                            </thead>
                            <tbody>
                                @foreach(session('import_errors') as $error)
                                <tr>
                                    <td>{{ $error['row'] }}</td>
                                    <td>{{ $error['attribute'] }}</td>
                                    <td>{{ implode(', ', $error['errors']) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        <div class="d-flex gap-2">
            <a href="{{ route('import.create') }}" class="btn btn-primary">Import Again</a>
            <a href="{{ route('items.index') }}" class="btn btn-secondary">View Items</a>
        </div>
    </div>
</div>
@endsection
