<x-app-layout>
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="mb-6">
            <h4 class="py-3 mb-0">
                <span class="text-muted fw-light">eCommerce /</span> Product List
            </h4>
        </div>

        <!-- Product List Widget -->
        <div class="card mb-6">
            <div class="card-widget-separator-wrapper">
                <div class="card-body card-widget-separator">
                    <div class="row gy-4 gy-sm-1">
                        <div class="col-sm-6 col-lg-3">
                            <div
                                class="d-flex justify-content-between align-items-start card-widget-1 border-end pb-4 pb-sm-0">
                                <div>
                                    <p class="mb-1">Total Produk</p>
                                    <h4 class="mb-1">{{ $products->count() }}</h4>
                                    <p class="mb-0">
                                        <span class="me-2">Semua Koleksi</span>
                                        <span class="badge bg-label-success">+12.4%</span>
                                    </p>
                                </div>
                                <span class="avatar me-sm-6">
                                    <span class="avatar-initial rounded"><i
                                            class="ti ti-box ti-md text-heading"></i></span>
                                </span>
                            </div>
                            <hr class="d-none d-sm-block d-lg-none me-6">
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div
                                class="d-flex justify-content-between align-items-start card-widget-2 border-end pb-4 pb-sm-0">
                                <div>
                                    <p class="mb-1">Stok Tersedia</p>
                                    <h4 class="mb-1">{{ number_format($products->sum('stock'), 0, ',', '.') }}</h4>
                                    <p class="mb-0">
                                        <span class="me-2">Unit Barang</span>
                                        <span class="badge bg-label-success">+5.7%</span>
                                    </p>
                                </div>
                                <span class="avatar p-2 me-lg-6">
                                    <span class="avatar-initial rounded"><i
                                            class="ti ti-packages ti-md text-heading"></i></span>
                                </span>
                            </div>
                            <hr class="d-none d-sm-block d-lg-none">
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div
                                class="d-flex justify-content-between align-items-start border-end pb-4 pb-sm-0 card-widget-3">
                                <div>
                                    <p class="mb-1">Produk Aktif</p>
                                    <h4 class="mb-1">{{ $products->where('is_active', true)->count() }}</h4>
                                    <p class="mb-0">Live di Toko</p>
                                </div>
                                <span class="avatar p-2 me-sm-6">
                                    <span class="avatar-initial rounded"><i
                                            class="ti ti-circle-check ti-md text-heading"></i></span>
                                </span>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="mb-1">Stok Menipis</p>
                                    <h4 class="mb-1">{{ $products->where('stock', '<=', 5)->count() }}</h4>
                                    <p class="mb-0">
                                        <span class="me-2">Perlu Restock</span>
                                        <span class="badge bg-label-danger">Low</span>
                                    </p>
                                </div>
                                <span class="avatar p-2">
                                    <span class="avatar-initial rounded"><i
                                            class="ti ti-alert-triangle ti-md text-heading"></i></span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product List Table -->
        <div class="card">
            <div class="card-header border-bottom d-flex justify-content-between align-items-center flex-wrap gap-3">
                <h5 class="card-title mb-0">Daftar Produk</h5>
                <div class="d-flex align-items-center">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal"
                        onclick="clearGallery('add_image_gallery')">
                        <i class="ti ti-plus me-1"></i> Add Product
                    </button>
                </div>
            </div>
            <div class="card-header border-bottom pb-4">
                <div class="d-flex justify-content-between align-items-center row gap-6 gap-md-0">
                    <div class="col-md-4 product_status"></div>
                    <div class="col-md-4 product_category"></div>
                    <div class="col-md-4 product_stock"></div>
                </div>
            </div>
            <div class="card-datatable table-responsive">
                <table class="datatables-products table table-hover border-top">
                    <thead>
                        <tr>
                            <th></th>
                            <th>PRODUK</th>
                            <th>KATEGORI</th>
                            <th>STOK</th>
                            <th>HARGA</th>
                            <th>STATUS</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $prod)
                        <tr>
                            <td></td>
                            <td>
                                <div class="d-flex justify-content-start align-items-center">
                                    <div class="avatar-wrapper me-3">
                                        <div class="avatar rounded-2 bg-label-secondary">
                                            @php
                                            $images = is_array($prod->images) ? $prod->images : [];
                                            $firstImg = count($images) > 0 ? $images[0] : null;
                                            $imgSrc = $firstImg ? (Str::startsWith($firstImg, 'http') ? $firstImg :
                                            asset('storage/'.$firstImg)) : asset('assets/img/elements/1.jpg');
                                            @endphp
                                            <img src="{{ $imgSrc }}" alt="Product" class="rounded-2"
                                                style="object-fit: cover;">
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="fw-medium text-heading text-nowrap">{{ $prod->name }}</span>
                                        <small class="text-muted text-truncate">{{ count($images) }} Foto | Berat: {{
                                            $prod->weight }}g</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class='text-truncate d-flex align-items-center text-heading'>
                                    <span
                                        class="avatar-sm rounded-circle d-flex justify-content-center align-items-center bg-label-info me-3">
                                        @if($prod->category->image)
                                            <img src="{{ Str::startsWith($prod->category->image, 'http') ? $prod->category->image : asset('storage/'.$prod->category->image) }}"
                                                alt="Category" class="rounded-circle"
                                                style="width: 100%; height: 100%; object-fit: cover;">
                                        @else
                                            <i class="ti ti-category ti-xs"></i>
                                        @endif
                                    </span>
                                    {{ $prod->category->name }}
                                </span>
                            </td>
                            <td>
                                <span class="fw-medium text-heading">{{ $prod->stock }}</span>
                            </td>
                            <td>
                                <span class="fw-bold text-primary">Rp {{ number_format($prod->price, 0, ',', '.')
                                    }}</span>
                            </td>
                            <td>
                                @if($prod->is_active)
                                <span class="badge bg-label-success">Active</span>
                                @else
                                <span class="badge bg-label-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <a href="{{ route('products.show', $prod->id) }}"
                                        class="btn btn-sm btn-icon btn-text-secondary rounded-pill waves-effect waves-light"><i
                                            class="ti ti-eye ti-md"></i></a>
                                    <button
                                        class="btn btn-sm btn-icon btn-text-secondary rounded-pill waves-effect waves-light edit-product"
                                        data-id="{{ $prod->id }}"><i class="ti ti-edit ti-md"></i></button>
                                    <button
                                        class="btn btn-sm btn-icon btn-text-secondary rounded-pill waves-effect waves-light delete-product"
                                        data-id="{{ $prod->id }}"><i class="ti ti-trash ti-md text-danger"></i></button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <!-- Modals -->
    @include('products.modals')

    <!-- Hidden Delete Form -->
    <form id="deleteProductForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    @push('scripts')
    <script>
        $(document).ready(function() {
            var dt_products = $('.datatables-products').DataTable({
                order: [[1, 'asc']],
                dom:
                    '<"card-header d-flex border-top rounded-0 flex-wrap py-0 flex-column flex-md-row align-items-center"' +
                    '<"me-auto"l>' +
                    '<"d-flex justify-content-start justify-content-md-end align-items-baseline"<"dt-action-buttons d-flex flex-column align-items-start align-items-sm-center justify-content-sm-center pt-0 gap-sm-4 gap-sm-0 flex-sm-row"f>>' +
                    '>t' +
                    '<"row"' +
                    '<"col-sm-12 col-md-6"i>' +
                    '<"col-sm-12 col-md-6"p>' +
                    '>',
                lengthMenu: [7, 10, 20, 50, 70, 100],
                language: {
                    sLengthMenu: '_MENU_',
                    search: "",
                    searchPlaceholder: "Search Product",
                    info: 'Displaying _START_ to _END_ of _TOTAL_ entries',
                    paginate: {
                        next: '<i class="ti ti-chevron-right ti-sm"></i>',
                        previous: '<i class="ti ti-chevron-left ti-sm"></i>'
                    }
                },
                columnDefs: [
                    {
                        // For Responsive
                        className: 'control',
                        searchable: false,
                        orderable: false,
                        targets: 0,
                        render: function (data, type, full, meta) {
                            return '';
                        }
                    }
                ],
                initComplete: function () {
                    // Adding status filter
                    this.api().columns(6).every(function () {
                        var column = this;
                        var select = $('<select id="ProductStatus" class="form-select text-capitalize"><option value="">Status</option></select>')
                            .appendTo('.product_status')
                            .on('change', function () {
                                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                                column.search(val ? '^' + val + '$' : '', true, false).draw();
                            });

                        column.data().unique().sort().each(function (d, j) {
                            var stripped = $(d).text().trim();
                            if(stripped) select.append('<option value="' + stripped + '">' + stripped + '</option>');
                        });
                    });

                    // Adding category filter
                    this.api().columns(2).every(function () {
                        var column = this;
                        var select = $('<select id="ProductCategory" class="form-select text-capitalize"><option value="">Category</option></select>')
                            .appendTo('.product_category')
                            .on('change', function () {
                                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                                column.search(val ? '^' + val + '$' : '', true, false).draw();
                            });

                        column.data().unique().sort().each(function (d, j) {
                            var stripped = $(d).text().trim();
                            if(stripped) select.append('<option value="' + stripped + '">' + stripped + '</option>');
                        });
                    });
                }
            });

            // Edit Product
            $(document).on('click', '.edit-product', function() {
                const id = $(this).data('id');
                $.get(`/products/${id}`, function(prod) {
                    $('#editProductForm').attr('action', `/products/${id}`);
                    $('#edit_name').val(prod.name);
                    $('#edit_category_id').val(prod.category_id);
                    $('#edit_price').val(prod.price);
                    $('#edit_old_price').val(prod.old_price);
                    $('#edit_stock').val(prod.stock);
                    $('#edit_weight').val(prod.weight);
                    $('#edit_rating').val(prod.rating);
                    $('#edit_sold_count').val(prod.sold_count);
                    $('#edit_description').val(prod.description);
                    $('#edit_status').val(prod.is_active ? 1 : 0);

                    // Variants
                    $('#edit_variant_1_name').val(prod.variant_1_name);
                    $('#edit_variant_1_options').val(prod.variant_1_options ? prod.variant_1_options.join(', ') : '');
                    $('#edit_variant_2_name').val(prod.variant_2_name);
                    $('#edit_variant_2_options').val(prod.variant_2_options ? prod.variant_2_options.join(', ') : '');
                    
                    // Populate Stack Gallery
                    const gallery = $('#edit_image_gallery');
                    gallery.empty();
                    
                    let urls = [];
                    if (prod.images && prod.images.length > 0) {
                        prod.images.forEach(img => {
                            let src = img.startsWith('http') ? img : `/storage/${img}`;
                            if(img.startsWith('http')) urls.push(img);
                            
                            gallery.append(`<img src="${src}" class="stacked-photo shadow">`);
                        });
                        $('#edit_image_urls').val(urls.join(', '));
                    } else {
                        gallery.append('<div class="text-center my-auto w-100"><i class="ti ti-photo text-muted" style="font-size: 2.5rem;"></i></div>');
                    }
                    
                    $('#editProductModal').modal('show');
                });
            });

            // ... delete logic remains ...
            $(document).on('click', '.delete-product', function() {
                const id = $(this).data('id');
                const form = $('#deleteProductForm');
                form.attr('action', `/products/${id}`);
                
                Swal.fire({
                    title: "Hapus produk ini?",
                    text: "Data produk akan hilang selamanya!",
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

        function clearGallery(id) {
            $(`#${id}`).html(`
                <div class="text-center my-auto placeholder-text w-100">
                    <i class="ti ti-photo-plus text-muted" style="font-size: 2.5rem;"></i>
                    <p class="text-muted small mt-1 font-bold">STAKED PREVIEW</p>
                </div>
            `);
        }

        function previewMultiImages(input, galleryId) {
            const gallery = $(`#${galleryId}`);
            gallery.find('.placeholder-text').remove();
            
            if (input.files) {
                Array.from(input.files).forEach(file => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        gallery.append(`<img src="${e.target.result}" class="stacked-photo shadow">`);
                    }
                    reader.readAsDataURL(file);
                });
            }
        }

        function updateUrlPreviews(textarea, galleryId) {
            const gallery = $(`#${galleryId}`);
            const urls = textarea.value.split(',').map(u => u.trim()).filter(u => u.length > 5);
            
            gallery.find('.url-preview').remove();
            gallery.find('.placeholder-text').remove();

            urls.forEach(url => {
                if(url.startsWith('http')) {
                    gallery.append(`<img src="${url}" class="stacked-photo url-preview shadow" onerror="this.remove()">`);
                }
            });
            
            if(gallery.children().length === 0) {
                clearGallery(galleryId);
            }
        }
    </script>
    @endpush
</x-app-layout>