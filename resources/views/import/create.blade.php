@extends('layouts.app')

@section('title', 'Import Items')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0">Import Items from Excel</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    Download template terlebih dahulu, lalu isi data sesuai format.
                </div>

                <div class="d-grid mb-4">
                    <a href="{{ route('export.template') }}" class="btn btn-outline-primary">
                        <i class="bi bi-download me-2"></i> Download Template Excel
                    </a>
                </div>

                <hr>

                <form method="POST" action="{{ route('import.process') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="file" class="form-label">File Excel <span class="text-danger">*</span></label>
                        <input type="file" class="form-control @error('file') is-invalid @enderror" id="file" name="file" accept=".xlsx,.xls,.csv" required>
                        @error('file') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <div class="form-text">Format: .xlsx, .xls, .csv (Max: 10MB)</div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-upload me-2"></i> Import Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
