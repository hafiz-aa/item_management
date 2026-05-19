<div class="card shadow-sm border-0 rounded-3">
    <div class="card-body p-4">
        <h5 class="fw-bold mb-3">Update Password</h5>
        <p class="text-muted small mb-4">Ensure your account is using a long, random password to stay secure.</p>

        <form method="post" action="{{ route('password.update') }}">
            @csrf
            @method('put')

            <div class="mb-3">
                <label for="current_password" class="form-label">Current Password</label>
                <input id="current_password" type="password" class="form-control @error('current_password') is-invalid @enderror" name="current_password" required>
                @error('current_password')
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

            <div class="d-flex align-items-center gap-3">
                <button type="submit" class="btn btn-primary">Save</button>
                @if(session('status') === 'password-updated')
                    <span class="text-success small"><i class="bi bi-check-circle"></i> Saved.</span>
                @endif
            </div>
        </form>
    </div>
</div>
