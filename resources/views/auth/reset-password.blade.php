@extends('layouts.guest')

@section('title', 'Reset Password')

@section('content')
<div class="card shadow-sm border-0 rounded-3">
    <div class="card-body p-4">
        <h5 class="card-title text-center mb-4 fw-bold">Reset Password</h5>

        <form method="POST" action="{{ route('password.store') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $request->email) }}" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">New Password</label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Reset Password</button>
            </div>
        </form>
    </div>
</div>
@endsection
