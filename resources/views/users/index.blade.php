<x-app-layout>
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="mb-6">
            <h4 class="py-3 mb-0">
                <span class="text-muted fw-light">Manajemen /</span> User Management
            </h4>
        </div>

        <!-- User Statistics Widgets -->
        <div class="row g-6 mb-6">
            <div class="col-sm-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="content-left">
                                <span class="text-heading">Total Users</span>
                                <div class="d-flex align-items-center my-1">
                                    <h4 class="mb-0 me-2">{{ $users->count() }}</h4>
                                    <span class="text-success">(+18%)</span>
                                </div>
                                <small class="mb-0">Total Pelanggan & Staff</small>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-primary">
                                    <i class="ti ti-users ti-md"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="content-left">
                                <span class="text-heading">User Aktif</span>
                                <div class="d-flex align-items-center my-1">
                                    <h4 class="mb-0 me-2">{{ $users->where('is_active', true)->count() }}</h4>
                                    <span class="text-success">(+5%)</span>
                                </div>
                                <small class="mb-0">Sedang Terdaftar</small>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-success">
                                    <i class="ti ti-user-check ti-md"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="content-left">
                                <span class="text-heading">Administrator</span>
                                <div class="d-flex align-items-center my-1">
                                    <h4 class="mb-0 me-2">{{ $admins->count() }}</h4>
                                    <span class="text-danger">(-2%)</span>
                                </div>
                                <small class="mb-0">Staff Pengelola</small>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-warning">
                                    <i class="ti ti-shield-check ti-md"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="content-left">
                                <span class="text-heading">Total Customer</span>
                                <div class="d-flex align-items-center my-1">
                                    <h4 class="mb-0 me-2">{{ $customers->count() }}</h4>
                                    <span class="text-success">(+12%)</span>
                                </div>
                                <small class="mb-0">Pelanggan Aktif</small>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-info">
                                    <i class="ti ti-shopping-cart ti-md"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header border-bottom d-flex justify-content-between align-items-center flex-wrap gap-3">
                <h5 class="card-title mb-0">List Pengguna</h5>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                    <i class="ti ti-plus me-1"></i> Add New User
                </button>
            </div>

            <div class="card-body pt-4">
                <!-- User Tabs -->
                <ul class="nav nav-tabs nav-fill mb-4" role="tablist">
                    <li class="nav-item">
                        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab"
                            data-bs-target="#tab-all">
                            <i class="ti ti-users me-1"></i> SEMUA USER
                            <span class="badge rounded-pill bg-label-primary ms-1">{{ $users->count() }}</span>
                        </button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                            data-bs-target="#tab-customer">
                            <i class="ti ti-shopping-cart me-1"></i> CUSTOMER
                            <span class="badge rounded-pill bg-label-success ms-1">{{ $customers->count() }}</span>
                        </button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                            data-bs-target="#tab-admin">
                            <i class="ti ti-shield-check me-1"></i> ADMIN
                            <span class="badge rounded-pill bg-label-warning ms-1">{{ $admins->count() }}</span>
                        </button>
                    </li>
                </ul>

                <div class="tab-content p-0 shadow-none">
                    <!-- Semua User Tab -->
                    <div class="tab-pane fade show active" id="tab-all" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table datatables-users table-hover border-top">
                                <thead>
                                    <tr>
                                        <th>USER</th>
                                        <th>ROLE</th>
                                        <th>PHONE</th>
                                        <th>STATUS</th>
                                        <th>ACTIONS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $u)
                                    <tr>
                                        <td>
                                            <div class="d-flex justify-content-start align-items-center user-name">
                                                <div class="avatar-wrapper me-3">
                                                    <div class="avatar avatar-sm">
                                                        <img src="{{ $u->profile_photo ? asset('storage/'.$u->profile_photo) : asset('assets/img/avatars/1.png') }}"
                                                            class="rounded-circle">
                                                    </div>
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <span class="fw-medium text-heading text-truncate">{{ $u->name
                                                        }}</span>
                                                    <small class="text-muted">{{ $u->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($u->role == 'admin')
                                            <span class="badge bg-label-warning text-uppercase">{{ $u->role }}</span>
                                            @else
                                            <span class="badge bg-label-success text-uppercase">{{ $u->role }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $u->phone ?? '-' }}</td>
                                        <td>
                                            @if($u->is_active)
                                            <span class="badge bg-label-success">Active</span>
                                            @else
                                            <span class="badge bg-label-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <button class="btn btn-sm btn-icon btn-text-secondary rounded-pill waves-effect waves-light edit-user" data-id="{{ $u->id }}"><i
                                                        class="ti ti-edit ti-md"></i></button>
                                                <button class="btn btn-sm btn-icon btn-text-secondary rounded-pill waves-effect waves-light delete-user"
                                                    data-id="{{ $u->id }}"><i
                                                        class="ti ti-trash ti-md text-danger"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Customer Tab -->
                    <div class="tab-pane fade" id="tab-customer" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table datatables-users table-hover border-top">
                                <thead>
                                    <tr>
                                        <th>USER</th>
                                        <th>PHONE</th>
                                        <th>CITY</th>
                                        <th>STATUS</th>
                                        <th>ACTIONS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($customers as $u)
                                    <tr>
                                        <td>
                                            <div class="d-flex justify-content-start align-items-center">
                                                <div class="avatar-wrapper me-3">
                                                    <div class="avatar avatar-sm">
                                                        <img src="{{ $u->profile_photo ? asset('storage/'.$u->profile_photo) : asset('assets/img/avatars/1.png') }}"
                                                            class="rounded-circle">
                                                    </div>
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <span class="fw-medium text-heading">{{ $u->name }}</span>
                                                    <small class="text-muted">{{ $u->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $u->phone ?? '-' }}</td>
                                        <td>{{ $u->city ?? '-' }}</td>
                                        <td>
                                            <span class="badge bg-label-success">Active</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <button class="btn btn-sm btn-icon btn-text-secondary rounded-pill waves-effect waves-light edit-user" data-id="{{ $u->id }}"><i
                                                        class="ti ti-edit ti-md"></i></button>
                                                <button class="btn btn-sm btn-icon btn-text-secondary rounded-pill waves-effect waves-light delete-user"
                                                    data-id="{{ $u->id }}"><i
                                                        class="ti ti-trash ti-md text-danger"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Admin Tab -->
                    <div class="tab-pane fade" id="tab-admin" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table datatables-users table-hover border-top">
                                <thead>
                                    <tr>
                                        <th>ADMIN NAME</th>
                                        <th>EMAIL</th>
                                        <th>PHONE</th>
                                        <th>ACTIONS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($admins as $u)
                                    <tr>
                                        <td>
                                            <div class="d-flex justify-content-start align-items-center">
                                                <div class="avatar-wrapper me-3">
                                                    <div class="avatar avatar-sm">
                                                        <img src="{{ $u->profile_photo ? asset('storage/'.$u->profile_photo) : asset('assets/img/avatars/1.png') }}"
                                                            class="rounded-circle">
                                                    </div>
                                                </div>
                                                <span class="fw-medium text-heading">{{ $u->name }}</span>
                                            </div>
                                        </td>
                                        <td>{{ $u->email }}</td>
                                        <td>{{ $u->phone ?? '-' }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <button class="btn btn-sm btn-icon btn-text-secondary rounded-pill waves-effect waves-light edit-user" data-id="{{ $u->id }}"><i
                                                        class="ti ti-edit ti-md"></i></button>
                                                @if($u->id != Auth::id())
                                                <button class="btn btn-sm btn-icon btn-text-secondary rounded-pill waves-effect waves-light delete-user"
                                                    data-id="{{ $u->id }}"><i
                                                        class="ti ti-trash ti-md text-danger"></i></button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->
    @include('users.modals')

    <!-- Hidden Delete Form -->
    <form id="deleteUserForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    @push('scripts')
    <script>
        $(document).ready(function() {
            $('.datatables-users').DataTable({
                order: [[0, 'desc']],
                dom:
                    '<"card-header d-flex border-top rounded-0 flex-wrap py-0 flex-column flex-md-row align-items-center"' +
                    '<"me-auto"l>' +
                    '<"d-flex justify-content-start justify-content-md-end align-items-baseline"<"dt-action-buttons d-flex flex-column align-items-start align-items-sm-center justify-content-sm-center pt-0 gap-sm-4 gap-sm-0 flex-sm-row"f>>' +
                    '>t' +
                    '<"row"' +
                    '<"col-sm-12 col-md-6"i>' +
                    '<"col-sm-12 col-md-6"p>' +
                    '>',
                language: {
                    search: "",
                    searchPlaceholder: "Search User..."
                }
            });

            // Edit User
            $(document).on('click', '.edit-user', function() {
                const id = $(this).data('id');
                $.get(`/users/${id}`, function(user) {
                    $('#editUserForm').attr('action', `/users/${id}`);
                    $('#edit_name').val(user.name);
                    $('#edit_email').val(user.email);
                    $('#edit_username').val(user.username);
                    $('#edit_phone').val(user.phone);
                    $('#edit_role').val(user.role);
                    $('#edit_status').val(user.is_active ? 1 : 0);
                    $('#editUserModal').modal('show');
                });
            });

            // Delete User with SweetAlert2
            $(document).on('click', '.delete-user', function() {
                const id = $(this).data('id');
                const form = $('#deleteUserForm');
                form.attr('action', `/users/${id}`);
                
                Swal.fire({
                    title: 'Apakah anda yakin?',
                    text: "Data user akan dihapus secara permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ea5455',
                    cancelButtonColor: '#a8aaae',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    customClass: {
                        confirmButton: 'btn btn-danger me-3',
                        cancelButton: 'btn btn-label-secondary'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
    @endpush
</x-app-layout>