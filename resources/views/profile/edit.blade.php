<x-app-layout>
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4">
            <span class="text-muted fw-light">User Profile /</span> Settings
        </h4>

        <!-- Header -->
        <div class="row">
            <div class="col-12">
                <div class="card mb-6">
                    <div class="user-profile-header-banner">
                        <img src="{{ asset('assets/img/pages/profile-banner.png') }}" alt="Banner image" class="rounded-top w-100" style="height: 150px; object-fit: cover;" />
                    </div>
                    <div class="user-profile-header d-flex flex-column flex-lg-row text-sm-start text-center mb-5">
                        <div class="flex-shrink-0 mt-n5 mx-sm-0 mx-auto">
                            <img src="{{ Auth::user()->profile_photo ? asset('storage/'.Auth::user()->profile_photo) : asset('assets/img/avatars/1.png') }}" 
                                 alt="user image" 
                                 class="d-block h-auto ms-0 ms-sm-6 rounded user-profile-img border border-4 border-white" 
                                 style="width: 120px; height: 120px; object-fit: cover;" />
                        </div>
                        <div class="flex-grow-1 mt-3 mt-lg-5">
                            <div class="d-flex align-items-md-end align-items-sm-start align-items-center justify-content-md-between justify-content-start mx-5 flex-md-row flex-column gap-4">
                                <div class="user-profile-info">
                                    <h4 class="mb-2 mt-lg-6">{{ Auth::user()->name }}</h4>
                                    <ul class="list-inline mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-4 my-2">
                                        <li class="list-inline-item d-flex gap-2 align-items-center">
                                            <i class="ti ti-shield-check ti-lg"></i><span class="fw-medium text-capitalize">{{ Auth::user()->role }}</span>
                                        </li>
                                        <li class="list-inline-item d-flex gap-2 align-items-center">
                                            <i class="ti ti-map-pin ti-lg"></i><span class="fw-medium">{{ Auth::user()->city ?? 'Indo' }}</span>
                                        </li>
                                        <li class="list-inline-item d-flex gap-2 align-items-center">
                                            <i class="ti ti-calendar ti-lg"></i><span class="fw-medium">Joined {{ Auth::user()->created_at->format('M Y') }}</span>
                                        </li>
                                    </ul>
                                </div>
                                <a href="javascript:void(0)" class="btn btn-primary mb-1 disabled text-white">
                                    <i class="ti ti-user-check ti-xs me-2"></i>Active Account
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/ Header -->

        <div class="row">
            <div class="col-md-12">
                <ul class="nav nav-pills flex-column flex-sm-row mb-6 gap-2 gap-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" href="javascript:void(0);"><i class="ti-sm ti ti-user-check me-1_5"></i> Profile Settings</a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-4 col-lg-5 col-md-5">
                <!-- Profile Image Edit -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Foto Profil</h5>
                        <form action="{{ route('account.profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="d-flex flex-column align-items-center">
                                <img id="admin-avatar-preview" 
                                     src="{{ Auth::user()->profile_photo ? asset('storage/'.Auth::user()->profile_photo) : asset('assets/img/avatars/1.png') }}" 
                                     class="rounded-circle mb-3 border border-2 border-primary" 
                                     style="width: 150px; height: 150px; object-fit: cover;">
                                <input type="file" name="profile_photo" class="form-control mb-2" onchange="previewAdminImage(this)">
                                <button type="submit" class="btn btn-sm btn-outline-primary w-100">Update Foto</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Account Overview -->
                <div class="card mb-4">
                    <div class="card-body text-center">
                        <i class="ti ti-badge ti-64px text-primary mb-3"></i>
                        <h5>Informasi Akun</h5>
                        <div class="text-start mt-4">
                            <p class="mb-1"><small class="text-muted">Username:</small> {{ Auth::user()->username }}</p>
                            <p class="mb-1"><small class="text-muted">E-mail:</small> {{ Auth::user()->email }}</p>
                            <p class="mb-0"><small class="text-muted">Role:</small> <span class="badge bg-label-warning text-uppercase">{{ Auth::user()->role }}</span></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-8 col-lg-7 col-md-7">
                <!-- Update Profile Info Form -->
                @include('profile.partials.update-profile-information-form')

                <!-- Update Password Form -->
                @include('profile.partials.update-password-form')

                <!-- Delete User Form -->
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function previewAdminImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('admin-avatar-preview').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
    @endpush
</x-app-layout>