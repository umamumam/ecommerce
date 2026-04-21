<section>
    <div class="card border-0 shadow-sm">
        <h5 class="card-header text-danger border-bottom mb-4">Delete Account</h5>
        <div class="card-body">
            <div class="mb-3 col-12 mb-0">
                <div class="alert alert-warning">
                    <h5 class="alert-heading mb-1">Are you sure you want to delete your account?</h5>
                    <p class="mb-0">Once you delete your account, there is no going back. Please be certain.</p>
                </div>
            </div>
            
            <button type="button" class="btn btn-danger deactivate-account" data-bs-toggle="modal" data-bs-target="#confirmDeleteUserModal">
                Delete Account
            </button>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="confirmDeleteUserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="post" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('delete')
                    <div class="modal-header">
                        <h5 class="modal-title">Delete Account Confirmation</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete your account? Please enter your password to confirm you would like to permanently delete your account.</p>
                        <div class="mt-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" id="password" name="password" class="form-control" placeholder="Password" />
                            @if($errors->userDeletion->get('password'))
                                <div class="text-danger small mt-1">{{ $errors->userDeletion->get('password')[0] }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Yes, Delete Account</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
