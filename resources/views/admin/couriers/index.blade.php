<x-app-layout>
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="flex items-center justify-between py-3 mb-4">
            <h4 class="fw-bold m-0"><span class="text-muted fw-light">Pengaturan /</span> Manajemen Kurir</h4>
            <div class="d-flex gap-2">
                <form action="{{ route('admin.couriers.sync') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-primary border-2">
                        <i class="ti ti-refresh me-1"></i> Sinkronkan dengan Biteship
                    </button>
                </form>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCourierModal">
                    <i class="ti ti-plus me-1"></i> Tambah Kurir
                </button>
            </div>
        </div>

        <div class="card">
            <div class="card-datatable table-responsive">
                <table class="table border-top">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Logo</th>
                            <th>Kode API</th>
                            <th>Nama Kurir</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($couriers as $index => $courier)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <div class="avatar avatar-sm bg-[#006d5b]/10 p-1 rounded">
                                    <i class="ti ti-truck text-[#006d5b]"></i>
                                </div>
                            </td>
                            <td><span class="badge bg-label-secondary uppercase">{{ $courier->code }}</span></td>
                            <td><span class="fw-bold">{{ $courier->name }}</span></td>
                            <td>
                                <div class="form-check form-switch">
                                    <input class="form-check-input courier-toggle" type="checkbox" 
                                           data-id="{{ $courier->id }}" {{ $courier->is_active ? 'checked' : '' }}>
                                    <label class="form-check-label">{{ $courier->is_active ? 'Aktif' : 'Nonaktif' }}</label>
                                </div>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-icon border-none shadow-none text-danger">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Add -->
    <div class="modal fade" id="addCourierModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Kurir Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.couriers.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Kode Kurir (Biteship Code)</label>
                            <input type="text" name="code" class="form-control" placeholder="jne, jnt, sicepat..." required>
                            <small class="text-muted">Gunakan huruf kecil semua tanpa spasi.</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Tampilan</label>
                            <input type="text" name="name" class="form-control" placeholder="JNE Express..." required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Kurir</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(function() {
            $('.courier-toggle').on('change', function() {
                const id = $(this).data('id');
                const label = $(this).siblings('.form-check-label');
                
                $.post(`/admin/couriers/${id}/toggle`, {
                    _token: '{{ csrf_token() }}'
                }, function(res) {
                    if(res.success) {
                        label.text(res.is_active ? 'Aktif' : 'Nonaktif');
                        Swal.fire({
                            icon: 'success',
                            title: 'Tersimpan',
                            text: res.message,
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                });
            });
        });
    </script>
    @endpush
</x-app-layout>
