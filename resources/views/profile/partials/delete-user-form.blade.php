<div class="card shadow-sm border-0 rounded-3 border-danger">
    <div class="card-body p-4">
        <h5 class="fw-bold mb-3 text-danger">Delete Account</h5>
        <p class="text-muted small mb-4">Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.</p>

        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
            Delete Account
        </button>

        <div class="modal fade" id="confirmDeleteModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirm Delete</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form method="post" action="{{ route('profile.destroy') }}">
                        @csrf
                        @method('delete')
                        <div class="modal-body">
                            <p class="text-muted">Are you sure you want to delete your account? Enter your password to confirm.</p>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input id="password" type="password" class="form-control" name="password" required>
                                @error('password')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">Delete Account</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
