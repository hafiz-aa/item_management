@extends('layouts.guest')

@section('title', 'Forgot Password')

@section('content')
<div class="card shadow-sm border-0 rounded-3">
    <div class="card-body p-4">
        <h5 class="card-title text-center mb-4 fw-bold">Forgot Password</h5>

        <div class="mb-3 text-sm text-muted">
            Forgot your password? No problem. Just let us know your email address and we will email you a password reset link.
        </div>

        @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Send Password Reset Link</button>
            </div>

            <div class="text-center mt-3">
                <a href="{{ route('login') }}" class="text-decoration-none small">Back to login</a>
            </div>
        </form>
    </div>
</div>
@endsection
