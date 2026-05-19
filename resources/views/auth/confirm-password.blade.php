@extends('layouts.guest')

@section('title', 'Confirm Password')

@section('content')
<div class="card shadow-sm border-0 rounded-3">
    <div class="card-body p-4">
        <h5 class="card-title text-center mb-4 fw-bold">Confirm Password</h5>

        <div class="mb-3 text-sm text-muted">
            This is a secure area of the application. Please confirm your password before continuing.
        </div>

        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Confirm</button>
            </div>
        </form>
    </div>
</div>
@endsection
