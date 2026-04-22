<x-app-layout>
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-6">
            <h4 class="py-3 mb-0">
                <span class="text-muted fw-light">Produk /</span> Detail: {{ $product->name }}
            </h4>
            <a href="{{ route('products.index') }}" class="btn btn-label-secondary">
                <i class="ti ti-arrow-left me-1"></i> Kembali ke Daftar
            </a>
        </div>

        <div class="row">
            <!-- Left: Gallery & Quick Stats -->
            <div class="col-xl-4 col-lg-5">
                <div class="card mb-6 shadow-sm">
                    <div class="card-body">
                        <div class="preview-stack-container mb-6 bg-label-secondary rounded-4 d-flex align-items-center justify-content-center p-4 overflow-hidden"
                            style="min-height: 280px; position: relative;">
                            @if(is_array($product->images) && count($product->images) > 0)
                            @foreach($product->images as $index => $img)
                            @php $src = Str::startsWith($img, 'http') ? $img : asset('storage/'.$img); @endphp
                            <img src="{{ $src }}" class="stacked-photo shadow-lg rounded-3"
                                style="width: 200px; height: 200px; object-fit: cover; z-index: {{ 10 - $index }}; margin-left: {{ $index > 0 ? '-140px' : '0' }}; transition: all 0.3s ease-in-out;">
                            @endforeach
                            @else
                            <div class="text-center text-muted">
                                <i class="ti ti-photo ti-xl mb-2"></i>
                                <p class="mb-0">Tidak ada gambar</p>
                            </div>
                            @endif
                        </div>
                        <div class="text-center">
                            <h4 class="mb-1 fw-black">{{ $product->name }}</h4>
                            <p class="text-muted mb-4">
                                <span class="badge bg-label-info me-1">{{ $product->category->name }}</span>
                                <span class="text-xs uppercase fw-bold tracking-tighter">SKU: PRD-{{
                                    str_pad($product->id, 5, '0', STR_PAD_LEFT) }}</span>
                            </p>

                            <div class="d-flex justify-content-center gap-2">
                                @if($product->is_active)
                                <span class="badge bg-success shadow-sm px-3 py-2"><i
                                        class="ti ti-circle-check me-1"></i> Aktif di Katalog</span>
                                @else
                                <span class="badge bg-secondary shadow-sm px-3 py-2"><i class="ti ti-eye-off me-1"></i>
                                    Hidden / Draft</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-6 shadow-sm">
                    <div class="card-header border-bottom d-flex align-items-center">
                        <i class="ti ti-chart-bar me-2 text-primary"></i>
                        <h6 class="card-title mb-0">Performa Produk</h6>
                    </div>
                    <div class="card-body pt-4">
                        <div
                            class="d-flex align-items-center justify-content-between mb-4 p-3 bg-label-success rounded-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar me-3">
                                    <span class="avatar-initial rounded bg-success"><i
                                            class="ti ti-shopping-cart"></i></span>
                                </div>
                                <span class="fw-medium">Total Terjual</span>
                            </div>
                            <h5 class="mb-0 fw-black">{{ $product->sold_count ?? 0 }} <small>Unit</small></h5>
                        </div>

                        <div class="row g-4">
                            <div class="col-6">
                                <div class="border rounded-3 p-3 text-center">
                                    <div class="text-warning mb-1"><i class="ti ti-star-filled ti-sm"></i></div>
                                    <h6 class="mb-0 fw-bold">{{ $product->rating ?? '5.0' }}</h6>
                                    <small class="text-muted text-xs">Rating</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="border rounded-3 p-3 text-center">
                                    <div class="text-info mb-1"><i class="ti ti-scale ti-sm"></i></div>
                                    <h6 class="mb-0 fw-bold">{{ $product->weight }}g</h6>
                                    <small class="text-muted text-xs">Berat</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Full Details -->
            <div class="col-xl-8 col-lg-7">
                <div class="card mb-6 shadow-sm">
                    <div class="card-header border-bottom d-flex justify-content-between align-items-center py-4">
                        <div class="d-flex align-items-center">
                            <i class="ti ti-info-circle me-2 text-primary"></i>
                            <h5 class="card-title mb-0">Informasi Detail Produk</h5>
                        </div>
                        <button class="btn btn-sm btn-primary waves-effect waves-light edit-product"
                            data-id="{{ $product->id }}">
                            <i class="ti ti-edit me-1"></i> Edit Data
                        </button>
                    </div>
                    <div class="card-body pt-5">
                        <div class="row g-6 mb-6">
                            <div class="col-md-6">
                                <div class="p-4 rounded-4 bg-label-primary border border-primary border-opacity-10">
                                    <label
                                        class="text-[10px] font-black uppercase text-primary tracking-widest d-block mb-1">Harga
                                        Jual Saat Ini</label>
                                    <h2 class="mb-0 fw-black text-primary">Rp {{ number_format($product->price, 0, ',',
                                        '.') }}</h2>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div
                                    class="p-4 rounded-4 border border-dashed h-100 flex-column justify-content-center d-flex">
                                    <label
                                        class="text-[10px] font-black uppercase text-muted tracking-widest d-block mb-1">Harga
                                        Original (Diskon)</label>
                                    @if($product->old_price)
                                    <h4 class="mb-0 text-muted text-decoration-line-through">Rp {{
                                        number_format($product->old_price, 0, ',', '.') }}</h4>
                                    @else
                                    <p class="mb-0 text-muted italic">Tidak ada harga diskon</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <h6 class="mb-4 d-flex align-items-center">
                                <span class="badge badge-dot bg-primary me-2"></span> Varian Produk
                            </h6>
                            <div class="row g-4">
                                @if($product->variant_1_name)
                                <div class="col-sm-6">
                                    <div class="card bg-label-secondary border-0 shadow-none">
                                        <div class="card-body p-4">
                                            <small class="text-xs fw-bold text-muted uppercase mb-2 d-block">{{
                                                $product->variant_1_name }}</small>
                                            <div class="d-flex flex-wrap gap-2">
                                                @foreach($product->variant_1_options as $opt)
                                                <span class="badge bg-white text-dark shadow-sm">{{ $opt }}</span>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                @if($product->variant_2_name)
                                <div class="col-sm-6">
                                    <div class="card bg-label-secondary border-0 shadow-none">
                                        <div class="card-body p-4">
                                            <small class="text-xs fw-bold text-muted uppercase mb-2 d-block">{{
                                                $product->variant_2_name }}</small>
                                            <div class="d-flex flex-wrap gap-2">
                                                @foreach($product->variant_2_options as $opt)
                                                <span class="badge bg-white text-dark shadow-sm">{{ $opt }}</span>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>

                        <div class="mb-0">
                            <h6 class="mb-4 d-flex align-items-center">
                                <span class="badge badge-dot bg-success me-2"></span> Manajemen Inventori
                            </h6>
                            <div
                                class="p-5 rounded-4 border-2 border-dashed {{ $product->stock < 5 ? 'border-danger bg-label-danger' : 'border-success bg-label-success' }} text-center">
                                <div class="d-flex align-items-center justify-content-center mb-2">
                                    <i class="ti ti-packages ti-lg me-2"></i>
                                    <h1 class="mb-0 fw-black">{{ $product->stock }}</h1>
                                </div>
                                <p class="mb-0 text-uppercase small fw-bold tracking-widest">Total Stok Tersedia di
                                    Gudang</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description Card -->
                <div class="card shadow-sm">
                    <div class="card-header border-bottom d-flex align-items-center py-4">
                        <i class="ti ti-align-left me-2 text-primary"></i>
                        <h6 class="card-title mb-0">Deskripsi Lengkap</h6>
                    </div>
                    <div class="card-body pt-4">
                        <div class="text-slate-600"
                            style="white-space: pre-wrap; line-height: 1.8; font-size: 0.95rem;">
                            {{ $product->description ?? 'Deskripsi produk belum ditambahkan.' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>