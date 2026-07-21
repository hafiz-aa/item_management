@extends('layouts.app')

@section('title', 'Edit Employee')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="fw-bold mb-0">Edit: {{ $employee->emp_code }} - {{ $employee->emp_name }}</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('employees.update', $employee) }}">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="emp_code" class="form-label">Kode Employee <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('emp_code') is-invalid @enderror" id="emp_code" name="emp_code" value="{{ old('emp_code', $employee->emp_code) }}" required>
                    @error('emp_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label for="emp_name" class="form-label">Nama Employee <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('emp_name') is-invalid @enderror" id="emp_name" name="emp_name" value="{{ old('emp_name', $employee->emp_name) }}" required>
                    @error('emp_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label for="branch_id" class="form-label">Branch <span class="text-danger">*</span></label>
                    <select class="form-select @error('branch_id') is-invalid @enderror" id="branch_id" name="branch_id" required>
                        <option value="">Pilih Branch</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->branch_id }}" {{ old('branch_id', $employee->branch_id) == $branch->branch_id ? 'selected' : '' }}>
                                {{ $branch->branch_code }} - {{ $branch->branch_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('branch_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label for="emp_sex" class="form-label">Jenis Kelamin</label>
                    <select class="form-select @error('emp_sex') is-invalid @enderror" id="emp_sex" name="emp_sex">
                        <option value="">Pilih</option>
                        <option value="0" {{ old('emp_sex', $employee->emp_sex) === '0' ? 'selected' : '' }}>Perempuan</option>
                        <option value="1" {{ old('emp_sex', $employee->emp_sex) === '1' ? 'selected' : '' }}>Laki-laki</option>
                    </select>
                    @error('emp_sex') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label for="emp_phone" class="form-label">Telepon</label>
                    <input type="text" class="form-control @error('emp_phone') is-invalid @enderror" id="emp_phone" name="emp_phone" value="{{ old('emp_phone', $employee->emp_phone) }}">
                    @error('emp_phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label for="emp_email" class="form-label">Email</label>
                    <input type="email" class="form-control @error('emp_email') is-invalid @enderror" id="emp_email" name="emp_email" value="{{ old('emp_email', $employee->emp_email) }}">
                    @error('emp_email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Update</button>
                <a href="{{ route('employees.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
