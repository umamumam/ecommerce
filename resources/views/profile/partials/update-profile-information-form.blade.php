<section>
    <div class="card mb-4">
        <h5 class="card-header border-bottom mb-4">Profil Admin</h5>
        <div class="card-body">
            <form method="post" action="{{ route('profile.update') }}">
                @csrf
                @method('patch')

                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label for="name" class="form-label">Nama Lengkap</label>
                        <input class="form-control" type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required autofocus />
                        @if($errors->get('name'))
                            <div class="text-danger small mt-1">{{ $errors->get('name')[0] }}</div>
                        @endif
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="username" class="form-label">Username</label>
                        <input class="form-control" type="text" id="username" name="username" value="{{ old('username', $user->username) }}" required />
                        @if($errors->get('username'))
                            <div class="text-danger small mt-1">{{ $errors->get('username')[0] }}</div>
                        @endif
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="email" class="form-label">E-mail</label>
                        <input class="form-control" type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required />
                        @if($errors->get('email'))
                            <div class="text-danger small mt-1">{{ $errors->get('email')[0] }}</div>
                        @endif
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="phone" class="form-label">Phone Number</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text">ID (+62)</span>
                            <input type="text" id="phone" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}" />
                        </div>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="province" class="form-label">Provinsi</label>
                        <input type="text" class="form-control" id="province" name="province" value="{{ old('province', $user->province) }}" />
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="city" class="form-label">Kota / Kabupaten</label>
                        <input type="text" class="form-control" id="city" name="city" value="{{ old('city', $user->city) }}" />
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="district" class="form-label">Kecamatan</label>
                        <input type="text" class="form-control" id="district" name="district" value="{{ old('district', $user->district) }}" />
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="postal_code" class="form-label">Kode Pos</label>
                        <input type="text" class="form-control" id="postal_code" name="postal_code" value="{{ old('postal_code', $user->postal_code) }}" />
                    </div>
                    <div class="mb-3 col-md-12">
                        <label for="address" class="form-label">Alamat Lengkap</label>
                        <textarea class="form-control" id="address" name="address" rows="3">{{ old('address', $user->address) }}</textarea>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary me-2">Save changes</button>
                    @if (session('status') === 'profile-updated')
                        <span class="text-success small animate-fade-in"><i class="ti ti-check me-1"></i> Saved.</span>
                    @endif
                </div>
            </form>
        </div>
    </div>
</section>
