<x-app-layout>
    <style>
        :root {
            --bg-body: #f8fafc;
            --text-dark: #1e293b;
            --text-muted: #64748b;
            --theme-green: #046c4e;
            --theme-green-light: #def7ec;
            --theme-green-text: #03543f;
            --border-color: #e2e8f0;
        }

        body {
            background-color: var(--bg-body);
        }

        .product-page-container {
            font-family: 'Inter', system-ui, sans-serif;
            padding-bottom: 100px;
        }

        /* --- Image Gallery Styles --- */
        .main-image-container {
            position: relative;
            width: 100%;
            max-width: 350px;
            background-color: #f1f5f9;
            border-radius: 1.5rem;
            overflow: hidden;
            aspect-ratio: 1 / 1;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
        }

        .main-product-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: opacity 0.3s ease-in-out;
        }

        .badge-original {
            position: absolute;
            top: 1rem;
            left: 1rem;
            background: rgba(255, 255, 255, 0.9);
            color: var(--theme-green);
            padding: 0.4rem 0.8rem;
            border-radius: 2rem;
            font-size: 0.75rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.3rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            z-index: 10;
        }

        .thumbnail-container {
            max-width: 350px;
            margin: 0 auto;
        }

        .thumbnail-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 0.5rem;
            cursor: pointer;
            border: 2px solid transparent;
            transition: all 0.2s;
        }

        .thumbnail-img.active {
            border-color: var(--theme-green);
            opacity: 1;
        }

        .thumbnail-img:not(.active) {
            opacity: 0.6;
        }

        /* --- Typography & Badges --- */
        .category-badge {
            background-color: var(--theme-green-light);
            color: var(--theme-green-text);
            font-size: 0.7rem;
            font-weight: 700;
            padding: 0.3rem 0.8rem;
            border-radius: 2rem;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .product-title {
            font-size: 1.4rem;
            font-weight: 800;
            color: var(--text-dark);
            line-height: 1.3;
            text-transform: uppercase;
        }

        .rating-badge {
            background-color: #fef08a;
            color: #854d0e;
            padding: 0.3rem 0.6rem;
            border-radius: 0.5rem;
            font-weight: 700;
            font-size: 0.85rem;
        }

        /* --- Main Box Detail --- */
        .detail-box {
            background: #ffffff;
            border-radius: 1.5rem;
            padding: 2rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.03);
        }

        .price-text {
            font-size: 2rem;
            font-weight: 900;
            color: var(--theme-green);
        }

        .variant-btn {
            border: 1px solid var(--border-color);
            background: transparent;
            color: var(--text-dark);
            padding: 0.5rem 1.2rem;
            border-radius: 0.5rem;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.2s;
        }

        .variant-btn:hover {
            border-color: var(--theme-green);
            color: var(--theme-green);
        }

        /* --- Compact Stats Box --- */
        .stat-box-compact {
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 1rem;
            padding: 1rem;
            display: flex;
            align-items: center;
            gap: 0.8rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.02);
            flex: 1;
        }

        .stat-icon-mini {
            width: 35px;
            height: 35px;
            background: var(--theme-green-light);
            color: var(--theme-green);
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>

    <div class="product-page-container pt-4">
        <div class="container-xxl">

            <div class="mb-4">
                <a href="{{ route('products.index') }}"
                    class="btn bg-white border shadow-sm rounded-pill px-4 py-2 text-dark fw-bold d-inline-flex align-items-center gap-2 transition-all">
                    <i class="ti ti-arrow-left"></i> Kembali
                </a>
            </div>

            <div class="row g-5">
                <div class="col-lg-4">
                    @php
                    $images = is_array($product->images) ? $product->images : [];
                    $hasImages = count($images) > 0;
                    $firstImgSrc = $hasImages ? (Str::startsWith($images[0], 'http') ? $images[0] :
                    asset('storage/'.$images[0])) : null;
                    @endphp

                    <div class="main-image-container shadow-sm mb-3">
                        <div class="badge-original">
                            <i class="ti ti-rosette-discount-check-filled"></i> ORIGINAL
                        </div>
                        @if($hasImages)
                        <img src="{{ $firstImgSrc }}" id="mainImage" class="main-product-img"
                            alt="{{ $product->name }}">
                        @else
                        <i class="ti ti-photo text-muted" style="font-size: 4rem;"></i>
                        @endif
                    </div>

                    @if($hasImages && count($images) > 1)
                    <div class="thumbnail-container d-flex gap-2 flex-wrap justify-content-center"
                        id="thumbnailContainer">
                        @foreach($images as $index => $img)
                        @php $src = Str::startsWith($img, 'http') ? $img : asset('storage/'.$img); @endphp
                        <img src="{{ $src }}" class="thumbnail-img {{ $index === 0 ? 'active' : '' }}"
                            data-index="{{ $index }}" onclick="changeImage({{ $index }}, '{{ $src }}')"
                            alt="Thumb {{ $index }}">
                        @endforeach
                    </div>
                    @endif

                    <div class="thumbnail-container d-flex gap-3 mt-4">
                        <div class="stat-box-compact">
                            <div class="stat-icon-mini">
                                <i class="ti ti-weight fs-5"></i>
                            </div>
                            <div>
                                <div class="text-muted fw-bold text-uppercase" style="font-size: 0.6rem;">Berat</div>
                                <div class="fw-bold text-dark" style="font-size: 0.85rem;">{{ $product->weight }} gr
                                </div>
                            </div>
                        </div>
                        <div class="stat-box-compact">
                            <div class="stat-icon-mini">
                                <i class="ti ti-box fs-5"></i>
                            </div>
                            <div>
                                <div class="text-muted fw-bold text-uppercase" style="font-size: 0.6rem;">Stok</div>
                                <div class="fw-bold text-dark" style="font-size: 0.85rem;">{{ $product->stock }} unit
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="mb-4">
                        <span class="category-badge mb-3 d-inline-block">{{ $product->category->name ?? 'Kategori'
                            }}</span>
                        <h1 class="product-title mb-3">{{ $product->name }}</h1>

                        <div class="d-flex align-items-center gap-3">
                            <div class="rating-badge d-flex align-items-center gap-1">
                                <i class="ti ti-star-filled"></i> 5.0
                            </div>
                            <span class="text-muted fw-semibold text-sm">{{ $product->sold_count ?? 0 }} Terjual</span>
                        </div>
                    </div>

                    <div class="detail-box mb-4">
                        <div class="mb-4 pb-3 border-bottom">
                            <div class="price-text">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                        </div>

                        @if($product->variant_1_name)
                        <div class="mb-4">
                            <label class="text-muted fw-bold text-uppercase mb-2"
                                style="font-size: 0.75rem; letter-spacing: 0.5px;">{{ $product->variant_1_name
                                }}</label>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($product->variant_1_options as $opt)
                                <button class="variant-btn">{{ $opt }}</button>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        @if($product->variant_2_name)
                        <div class="mb-4">
                            <label class="text-muted fw-bold text-uppercase mb-2"
                                style="font-size: 0.75rem; letter-spacing: 0.5px;">{{ $product->variant_2_name
                                }}</label>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($product->variant_2_options as $opt)
                                <button class="variant-btn">{{ $opt }}</button>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <div class="mt-4 pt-3 border-top">
                            <h6 class="fw-bold text-dark text-uppercase mb-3" style="font-size: 0.85rem;">Deskripsi
                                Produk</h6>
                            <div class="text-secondary"
                                style="font-size: 0.95rem; line-height: 1.7; white-space: pre-line;">
                                {{ $product->description ?? 'Tidak ada deskripsi untuk produk ini.' }}
                            </div>
                        </div>

                        <div class="d-flex gap-3 mt-4">
                            <button class="btn btn-dark fw-bold px-4 py-2" style="border-radius: 0.5rem; flex: 1;">
                                <i class="ti ti-pencil"></i> EDIT PRODUK
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    @if($hasImages && count($images) > 1)
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const images = [
                @foreach($images as $img)
                    "{{ Str::startsWith($img, 'http') ? $img : asset('storage/'.$img) }}",
                @endforeach
            ];
            
            let currentIndex = 0;
            const mainImageEl = document.getElementById('mainImage');
            const thumbnails = document.querySelectorAll('.thumbnail-img');
            let slideInterval;

            window.changeImage = function(index, src) {
                currentIndex = index;
                
                mainImageEl.style.opacity = '0.5';
                setTimeout(() => {
                    mainImageEl.src = src;
                    mainImageEl.style.opacity = '1';
                }, 150);

                thumbnails.forEach(t => t.classList.remove('active'));
                if (thumbnails[index]) {
                    thumbnails[index].classList.add('active');
                }

                resetInterval();
            };

            function autoSlide() {
                currentIndex = (currentIndex + 1) % images.length;
                changeImage(currentIndex, images[currentIndex]);
            }

            function startInterval() {
                slideInterval = setInterval(autoSlide, 5000);
            }

            function resetInterval() {
                clearInterval(slideInterval);
                startInterval();
            }

            startInterval();
        });
    </script>
    @endif
</x-app-layout>