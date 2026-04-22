<!-- Add Product Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Produk Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <style>
                        .preview-stack-container {
                            display: flex;
                            flex-wrap: wrap;
                            gap: 10px;
                            justify-content: center;
                            padding: 20px 10px;
                        }
                        .stacked-photo {
                            width: 100px;
                            height: 100px;
                            object-fit: cover;
                            border-radius: 12px;
                            border: 3px solid white;
                            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
                            transition: all 0.3s ease;
                            margin-right: -40px;
                        }
                        .stacked-photo:hover {
                            transform: translateY(-10px) rotate(3deg);
                            margin-right: 10px;
                            z-index: 10;
                        }
                        .stacked-photo:last-child {
                            margin-right: 0;
                        }
                    </style>
                    <div class="row">
                        <div class="col-md-4 text-center border-end">
                            <!-- Stack Preview Gallery -->
                            <div id="add_image_gallery" class="preview-stack-container mb-3 bg-light rounded shadow-inner" style="min-height: 140px;">
                                <div class="text-center my-auto placeholder-text w-100">
                                    <i class="ti ti-photo-plus text-muted" style="font-size: 2.5rem;"></i>
                                    <p class="text-muted small mt-1 font-bold">STAKED PREVIEW</p>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-[10px] font-bold uppercase">Upload Foto (Bisa >1)</label>
                                <input type="file" name="images[]" class="form-control form-control-sm" multiple
                                    onchange="previewMultiImages(this, 'add_image_gallery')">
                            </div>

                            <div class="mb-3 text-start">
                                <label class="form-label text-[10px] font-bold uppercase text-primary">Atau Pakai Link
                                    Image</label>
                                <textarea name="image_urls" class="form-control form-control-sm url-input" rows="3"
                                    placeholder="https://link.com/foto1.jpg, https://link.com/foto2.jpg"
                                    oninput="updateUrlPreviews(this, 'add_image_gallery')"></textarea>
                                <small class="text-muted" style="font-size: 9px;">Pisahkan dengan koma jika lebih dari
                                    satu link.</small>
                            </div>

                            <hr class="my-4">

                            <!-- Variants Section -->
                            <div class="text-start">
                                <h6 class="text-[10px] font-black uppercase mb-3 text-primary tracking-widest"><i
                                        class="ti ti-layers-intersect me-1"></i>Varian Produk</h6>

                                <div class="mb-3">
                                    <label class="form-label text-[10px] font-bold uppercase">Opsi 1 (Misal:
                                        Ukuran)</label>
                                    <input type="text" name="variant_1_name" class="form-control form-control-sm"
                                        placeholder="Nama Varian">
                                    <input type="text" name="variant_1_options"
                                        class="form-control form-control-sm mt-1" placeholder="S, M, L, XL">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label text-[10px] font-bold uppercase">Opsi 2 (Misal:
                                        Warna)</label>
                                    <input type="text" name="variant_2_name" class="form-control form-control-sm"
                                        placeholder="Nama Varian">
                                    <input type="text" name="variant_2_options"
                                        class="form-control form-control-sm mt-1" placeholder="Merah, Biru">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="form-label font-bold">Nama Produk</label>
                                <input type="text" name="name" class="form-control" required
                                    placeholder="Contoh: Kemeja Flanel Slim Fit">
                            </div>
                            <!-- ... rest of form ... -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Kategori</label>
                                    <select name="category_id" class="form-select" required>
                                        <option value="">Pilih Kategori</option>
                                        @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Berat (Gram)</label>
                                    <input type="number" name="weight" class="form-control" required placeholder="1000">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Harga Jual (Rp)</label>
                                    <input type="number" name="price" class="form-control" required placeholder="0">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Harga Coret / Asli (Rp)</label>
                                    <input type="number" name="old_price" class="form-control" placeholder="Optional">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Stok</label>
                                    <input type="number" name="stock" class="form-control" required placeholder="0">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Rating</label>
                                    <input type="number" step="0.1" name="rating" class="form-control"
                                        placeholder="4.5">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Terjual</label>
                                    <input type="number" name="sold_count" class="form-control" placeholder="100">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Deskripsi Produk</label>
                                <textarea name="description" class="form-control" rows="8"
                                    placeholder="Jelaskan detail produk..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top pt-3">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary shadow-lg px-5">SIMPAN PRODUK</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Product Modal -->
<div class="modal fade" id="editProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Detail Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editProductForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 text-center border-end">
                            <!-- Stack Preview Gallery -->
                            <div id="edit_image_gallery" class="preview-stack-container mb-3 bg-light rounded shadow-inner" style="min-height: 140px;">
                                <!-- Will be filled by JS -->
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-[10px] font-bold uppercase">Update Upload Foto</label>
                                <input type="file" name="images[]" class="form-control form-control-sm" multiple
                                    onchange="previewMultiImages(this, 'edit_image_gallery')">
                            </div>

                            <div class="mb-3 text-start">
                                <label class="form-label text-[10px] font-bold uppercase text-primary">Update Link
                                    Image</label>
                                <textarea name="image_urls" id="edit_image_urls"
                                    class="form-control form-control-sm url-input" rows="3"
                                    placeholder="https://link.com/foto1.jpg"
                                    oninput="updateUrlPreviews(this, 'edit_image_gallery')"></textarea>
                                <small class="text-muted" style="font-size: 9px;">Kosongi jika tidak ingin mengubah foto
                                    lama.</small>
                            </div>

                            <hr class="my-4">

                            <!-- Variants Section -->
                            <!-- ... variants content ... -->
                            <div class="text-start">
                                <h6 class="text-[10px] font-black uppercase mb-3 text-primary tracking-widest"><i
                                        class="ti ti-layers-intersect me-1"></i>Varian Produk</h6>

                                <div class="mb-3">
                                    <label class="form-label text-[10px] font-bold uppercase">Opsi 1</label>
                                    <input type="text" name="variant_1_name" id="edit_variant_1_name"
                                        class="form-control form-control-sm" placeholder="Nama Varian">
                                    <input type="text" name="variant_1_options" id="edit_variant_1_options"
                                        class="form-control form-control-sm mt-1" placeholder="S, M, L">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label text-[10px] font-bold uppercase">Opsi 2</label>
                                    <input type="text" name="variant_2_name" id="edit_variant_2_name"
                                        class="form-control form-control-sm" placeholder="Nama Varian">
                                    <input type="text" name="variant_2_options" id="edit_variant_2_options"
                                        class="form-control form-control-sm mt-1" placeholder="Warna">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="form-label font-bold">Nama Produk</label>
                                <input type="text" name="name" id="edit_name" class="form-control" required>
                            </div>
                            <!-- ... rest of form ... -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Kategori</label>
                                    <select name="category_id" id="edit_category_id" class="form-select" required>
                                        @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Berat (Gram)</label>
                                    <input type="number" name="weight" id="edit_weight" class="form-control" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Harga Jual (Rp)</label>
                                    <input type="number" name="price" id="edit_price" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Harga Coret / Asli (Rp)</label>
                                    <input type="number" name="old_price" id="edit_old_price" class="form-control">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Stok</label>
                                    <input type="number" name="stock" id="edit_stock" class="form-control" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Rating</label>
                                    <input type="number" step="0.1" name="rating" id="edit_rating" class="form-control">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Terjual</label>
                                    <input type="number" name="sold_count" id="edit_sold_count" class="form-control">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Status Produk</label>
                                <select name="is_active" id="edit_status" class="form-select" required>
                                    <option value="1">Aktif (Tampil)</option>
                                    <option value="0">Draft (Sembunyikan)</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Deskripsi Produk</label>
                                <textarea name="description" id="edit_description" class="form-control"
                                    rows="8"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top pt-3">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary shadow-lg px-5">UPDATE DATA PRODUK</button>
                </div>
            </form>
        </div>
    </div>
</div>