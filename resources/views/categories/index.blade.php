<x-app-layout>
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="mb-4">
            <h4 class="py-3 mb-0">
                <span class="text-muted fw-light">Produk /</span> Manajemen Kategori
            </h4>
        </div>

        <div class="card">
            <div class="card-header border-bottom d-flex justify-content-between align-items-center flex-wrap gap-3">
                <h5 class="card-title mb-0">Daftar Kategori Produk</h5>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                    <i class="ti ti-plus me-1"></i> Tambah Kategori
                </button>
            </div>
            <div class="card-body pt-4">
                <div class="table-responsive">
                    <table class="table datatables-categories table-hover border-top">
                        <thead>
                            <tr>
                                <th>KATEGORI</th>
                                <th>SLUG</th>
                                <th>STATUS</th>
                                <th>ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $cat)
                            <tr>
                                <td>
                                    <div class="d-flex justify-content-start align-items-center">
                                        <div class="avatar-wrapper me-3">
                                            <div class="avatar avatar-sm">
                                                <img src="{{ $cat->image ? asset('storage/'.$cat->image) : asset('assets/img/elements/1.jpg') }}" alt="Category" class="rounded" style="object-fit: cover;">
                                            </div>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <span class="fw-medium text-heading">{{ $cat->name }}</span>
                                            <small class="text-muted line-clamp-1">{{ $cat->description ?? 'Tidak ada deskripsi' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td><code class="text-primary">{{ $cat->slug }}</code></td>
                                <td>
                                    @if($cat->is_active)
                                        <span class="badge bg-label-success">Aktif</span>
                                    @else
                                        <span class="badge bg-label-secondary">Nonaktif</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <button class="btn btn-sm btn-icon edit-category" data-id="{{ $cat->id }}"><i class="ti ti-edit"></i></button>
                                        <button class="btn btn-sm btn-icon delete-category" data-id="{{ $cat->id }}"><i class="ti ti-trash text-danger"></i></button>
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

    <!-- Modals -->
    @include('categories.modals')

    <!-- Hidden Delete Form -->
    <form id="deleteCategoryForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    @push('scripts')
    <script>
        $(document).ready(function() {
            $('.datatables-categories').DataTable({
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
                    searchPlaceholder: "Cari Kategori..."
                }
            });

            // Edit Category
            $(document).on('click', '.edit-category', function() {
                const id = $(this).data('id');
                $.get(`/admin/categories/${id}`, function(cat) {
                    $('#editCategoryForm').attr('action', `/admin/categories/${id}`);
                    $('#edit_name').val(cat.name);
                    $('#edit_description').val(cat.description);
                    $('#edit_status').val(cat.is_active ? 1 : 0);
                    
                    if (cat.image) {
                        $('#edit_image_preview').attr('src', `/storage/${cat.image}`);
                    } else {
                        $('#edit_image_preview').attr('src', '/assets/img/elements/1.jpg');
                    }
                    
                    $('#editCategoryModal').modal('show');
                });
            });

            // Delete Category
            $(document).on('click', '.delete-category', function() {
                const id = $(this).data('id');
                const form = $('#deleteCategoryForm');
                form.attr('action', `/admin/categories/${id}`);
                
                Swal.fire({
                    title: "Apakah anda yakin?",
                    text: "Kategori ini akan dihapus permanen!",
                    icon: "warning",
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

        function previewCategoryImage(input, previewId) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById(previewId).src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
    @endpush
</x-app-layout>
