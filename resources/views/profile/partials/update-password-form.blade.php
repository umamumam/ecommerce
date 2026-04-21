<section>
    <div class="card mb-4">
        <h5 class="card-header border-bottom mb-4">Update Password</h5>
        <div class="card-body">
            <form method="post" action="{{ route('password.update') }}">
                @csrf
                @method('put')

                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label for="update_password_password" class="form-label">New Password</label>
                        <input class="form-control" type="password" id="update_password_password" name="password" autocomplete="new-password" />
                        @if($errors->updatePassword->get('password'))
                            <div class="text-danger small mt-1">{{ $errors->updatePassword->get('password')[0] }}</div>
                        @endif
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="update_password_password_confirmation" class="form-label">Confirm Password</label>
                        <input class="form-control" type="password" id="update_password_password_confirmation" name="password_confirmation" autocomplete="new-password" />
                        @if($errors->updatePassword->get('password_confirmation'))
                            <div class="text-danger small mt-1">{{ $errors->updatePassword->get('password_confirmation')[0] }}</div>
                        @endif
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary me-2">Change Password</button>
                    @if (session('status') === 'password-updated')
                        <span class="text-success small"><i class="ti ti-check me-1"></i> Saved.</span>
                    @endif
                </div>
            </form>
        </div>
    </div>
</section>
