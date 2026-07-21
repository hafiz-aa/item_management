@extends('layouts.app')

@section('title', 'Edit Customer')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="fw-bold mb-0">Edit: {{ $customer->cust_code }} - {{ $customer->cust_name }}</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('customers.update', $customer) }}">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="cust_code" class="form-label">Kode Customer <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('cust_code') is-invalid @enderror" id="cust_code" name="cust_code" value="{{ old('cust_code', $customer->cust_code) }}" required>
                    @error('cust_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label for="cust_name" class="form-label">Nama Customer <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('cust_name') is-invalid @enderror" id="cust_name" name="cust_name" value="{{ old('cust_name', $customer->cust_name) }}" required>
                    @error('cust_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label for="custtp_id" class="form-label">Tipe Customer <span class="text-danger">*</span></label>
                    <select class="form-select @error('custtp_id') is-invalid @enderror" id="custtp_id" name="custtp_id" required>
                        <option value="">Pilih Tipe</option>
                        @foreach($customerTypes as $type)
                            <option value="{{ $type->custtp_id }}" {{ old('custtp_id', $customer->custtp_id) == $type->custtp_id ? 'selected' : '' }}>
                                {{ $type->custtp_code }} - {{ $type->custtp_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('custtp_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label for="cust_address" class="form-label">Alamat</label>
                    <textarea class="form-control @error('cust_address') is-invalid @enderror" id="cust_address" name="cust_address" rows="2">{{ old('cust_address', $customer->cust_address) }}</textarea>
                    @error('cust_address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label for="cust_phone" class="form-label">Telepon</label>
                    <input type="text" class="form-control @error('cust_phone') is-invalid @enderror" id="cust_phone" name="cust_phone" value="{{ old('cust_phone', $customer->cust_phone) }}">
                    @error('cust_phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label for="cust_fax" class="form-label">Fax</label>
                    <input type="text" class="form-control @error('cust_fax') is-invalid @enderror" id="cust_fax" name="cust_fax" value="{{ old('cust_fax', $customer->cust_fax) }}">
                    @error('cust_fax') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label for="cust_email" class="form-label">Email</label>
                    <input type="email" class="form-control @error('cust_email') is-invalid @enderror" id="cust_email" name="cust_email" value="{{ old('cust_email', $customer->cust_email) }}">
                    @error('cust_email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label for="cust_status" class="form-label">Status <span class="text-danger">*</span></label>
                    <select class="form-select @error('cust_status') is-invalid @enderror" id="cust_status" name="cust_status" required>
                        <option value="0" {{ old('cust_status', $customer->cust_status) === '0' ? 'selected' : '' }}>Active</option>
                        <option value="1" {{ old('cust_status', $customer->cust_status) === '1' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('cust_status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Update</button>
                <a href="{{ route('customers.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
