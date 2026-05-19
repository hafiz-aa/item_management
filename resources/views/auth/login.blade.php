@extends('layouts.guest')

@section('title', 'Login')

@section('content')
<div class="card shadow-sm border-0 rounded-3">
    <div class="card-body p-4">
        <h5 class="card-title text-center mb-4 fw-bold">Sign In</h5>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                <label class="form-check-label" for="remember">Remember me</label>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-lg">Sign In</button>
            </div>

            <div class="text-center mt-3">
                <a href="{{ route('password.request') }}" class="text-decoration-none small">Forgot password?</a>
            </div>
        </form>
    </div>
</div>
<div class="text-center mt-3">
    <small class="text-muted">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</small>
</div>
@endsection
