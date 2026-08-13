<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Koleksi Produk | {{ config('app.name', 'TOKO KITA') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700;900&display=swap"
        rel="stylesheet" />

    <!-- Icons (Tabler) -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/tabler-icons.css') }}" />

    <!-- Scripts / Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Public Sans', sans-serif;
            background-color: #fcfcfc;
        }

        .primary-teal {
            color: #006d5b;
        }

        .bg-primary-teal {
            background-color: #006d5b;
        }

        .categories-scroll {
            display: flex;
            overflow-x: auto;
            white-space: nowrap;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
        }

        .categories-scroll::-webkit-scrollbar {
            display: none;
        }
    </style>
</head>

<body class="antialiased pb-20 lg:pb-0">

    <!-- Desktop Header -->
    <header class="hidden lg:block bg-white shadow-md sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between">
            <div class="flex items-center gap-12 flex-1">
                <a href="/" class="text-2xl font-black flex items-center">
                    <span class="text-[#006d5b]">TOKO</span><span class="text-[#f53003]">KITA</span>
                </a>
                <form action="{{ route('catalog.index') }}" method="GET" class="relative flex-1 max-w-lg">
                    @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    <input type="text" name="q" value="{{ $searchTerm }}"
                        class="w-full bg-slate-100 border-none rounded-lg py-2.5 px-10 focus:ring-2 focus:ring-[#006d5b] text-sm"
                        placeholder="Apa yang kamu cari hari ini?">
                    <button type="submit" class="absolute left-3 top-3 text-slate-400 hover:text-[#006d5b]">
                        <i class="ti ti-search"></i>
                    </button>
                </form>
            </div>

            <nav class="flex items-center gap-6 ml-8">
                <div class="flex items-center gap-6 border-r pr-6">
                    <a href="{{ auth()->check() ? (auth()->user()->role === 'admin' ? route('dashboard') : route('account')) : route('login') }}"
                        class="flex flex-col items-center text-slate-700 hover:text-[#006d5b] transition group">
                        @auth
                        <img src="{{ auth()->user()->profile_photo ? asset('storage/'.auth()->user()->profile_photo) : asset('assets/img/avatars/1.png') }}"
                            class="w-6 h-6 rounded-full object-cover group-hover:scale-110 transition border border-slate-100 shadow-sm">
                        @else
                        <i class="ti ti-user ti-sm group-hover:scale-110 transition"></i>
                        @endauth
                        <span class="text-[9px] font-bold uppercase mt-1">Akun</span>
                    </a>
                    <a href="{{ route('cart.index') }}"
                        class="flex flex-col items-center text-slate-700 hover:text-[#006d5b] transition relative group">
                        <i class="ti ti-shopping-cart ti-sm group-hover:scale-110 transition"></i>
                        <span class="text-[9px] font-bold uppercase mt-1">Cart</span>
                        @if(count(session('cart', [])) > 0)
                        <span
                            class="absolute -top-1.5 -right-2 bg-[#f53003] text-white text-[8px] w-4 h-4 flex items-center justify-center rounded-full border-2 border-white font-black animate-bounce">{{
                            count(session('cart', [])) }}</span>
                        @endif
                    </a>
                </div>
                @auth
                <a href="{{ route('dashboard') }}" class="btn-primary-teal px-5 py-2 rounded-lg text-xs">DASHBOARD</a>
                @else
                <a href="{{ route('login') }}" class="btn-primary-teal px-5 py-2 rounded-lg text-xs">MASUK / DAFTAR</a>
                @endauth
            </nav>
        </div>
    </header>

    <!-- Mobile Header -->
    <header class="lg:hidden bg-white p-4 sticky top-0 z-50 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <a href="/" class="text-xl font-black flex items-center">
                <span class="text-[#006d5b]">TOKO</span><span class="text-[#f53003]">KITA</span>
            </a>
            <div class="flex items-center gap-5">
                <a href="{{ auth()->check() ? (auth()->user()->role === 'admin' ? route('dashboard') : route('account')) : route('login') }}"
                    class="flex flex-col items-center text-slate-800">
                    @auth
                    <img src="{{ auth()->user()->profile_photo ? asset('storage/'.auth()->user()->profile_photo) : asset('assets/img/avatars/1.png') }}"
                        class="w-7 h-7 rounded-full object-cover border border-slate-100 shadow-sm">
                    @else
                    <i class="ti ti-user text-xl"></i>
                    @endauth
                    <span class="text-[8px] font-bold uppercase mt-0.5">Akun</span>
                </a>
                <a href="{{ route('cart.index') }}" class="relative flex flex-col items-center text-slate-800">
                    <i class="ti ti-shopping-cart text-xl"></i>
                    <span class="text-[8px] font-bold uppercase">Cart</span>
                    @if(count(session('cart', [])) > 0)
                    <span
                        class="absolute -top-1 -right-2 bg-[#f53003] text-white text-[8px] w-4 h-4 flex items-center justify-center rounded-full border border-white font-bold">{{
                        count(session('cart', [])) }}</span>
                    @endif
                </a>
            </div>
        </div>
        <form action="{{ route('catalog.index') }}" method="GET" class="relative">
            @if(request('category'))
            <input type="hidden" name="category" value="{{ request('category') }}">
            @endif
            <input type="text" name="q" value="{{ $searchTerm }}"
                class="w-full bg-slate-100/90 border-none rounded-lg py-1.5 pl-9 pr-3 text-[11px] placeholder-slate-400 focus:ring-1 focus:ring-[#006d5b] h-9"
                placeholder="Cari fashion terbaru...">
            <button type="submit" class="absolute left-3 top-2.5 text-slate-400 hover:text-[#006d5b]">
                <i class="ti ti-search text-sm"></i>
            </button>
        </form>
    </header>

    <main class="max-w-6xl mx-auto px-4 lg:px-0 py-6">

        <!-- Breadcrumb & Title Section -->
        <div class="mb-6 bg-white p-5 lg:p-8 rounded-2xl lg:rounded-3xl border border-slate-100 shadow-sm">
            <div class="flex items-center gap-2 text-xs text-slate-400 mb-2 font-medium">
                <a href="/" class="hover:text-[#006d5b] transition">Beranda</a>
                <i class="ti ti-chevron-right text-[10px]"></i>
                <span class="text-slate-700 font-bold">Koleksi Produk</span>
            </div>
            
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-xl lg:text-3xl font-black text-slate-800 uppercase tracking-tight">
                        @if($currentCategory)
                            Kategori: {{ $currentCategory->name }}
                        @elseif($searchTerm)
                            Hasil Pencarian: "{{ $searchTerm }}"
                        @else
                            Semua Koleksi Produk
                        @endif
                    </h1>
                    <p class="text-xs text-slate-500 mt-1">
                        Menampilkan {{ $products->total() }} produk tersedia
                    </p>
                </div>

                <!-- Sorting Dropdown -->
                <form action="{{ route('catalog.index') }}" method="GET" class="flex items-center gap-2 shrink-0">
                    @if($searchTerm)
                    <input type="hidden" name="q" value="{{ $searchTerm }}">
                    @endif
                    @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    <label class="text-xs font-bold text-slate-600 whitespace-nowrap">Urutkan:</label>
                    <select name="sort" onchange="this.form.submit()"
                        class="bg-slate-100 border-none rounded-xl text-xs py-2 px-3 focus:ring-2 focus:ring-[#006d5b] font-bold text-slate-700 cursor-pointer">
                        <option value="latest" {{ $sort === 'latest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="price_low" {{ $sort === 'price_low' ? 'selected' : '' }}>Harga: Rendah ke Tinggi</option>
                        <option value="price_high" {{ $sort === 'price_high' ? 'selected' : '' }}>Harga: Tinggi ke Rendah</option>
                        <option value="rating" {{ $sort === 'rating' ? 'selected' : '' }}>Rating Tertinggi</option>
                    </select>
                </form>
            </div>
        </div>

        <!-- Category Pills Filter -->
        <div class="mb-8">
            <div class="flex items-center gap-3 categories-scroll pb-2">
                <a href="{{ route('catalog.index', array_filter(['q' => $searchTerm, 'sort' => $sort])) }}"
                    class="px-5 py-2.5 rounded-full text-xs font-black uppercase tracking-wider transition shrink-0 border {{ !$currentCategory ? 'bg-[#006d5b] text-white border-[#006d5b] shadow-md' : 'bg-white text-slate-600 border-slate-200 hover:border-[#006d5b] hover:text-[#006d5b]' }}">
                    Semua Kategori
                </a>
                @foreach($categories as $cat)
                <a href="{{ route('catalog.index', array_filter(['category' => $cat->slug, 'q' => $searchTerm, 'sort' => $sort])) }}"
                    class="px-5 py-2.5 rounded-full text-xs font-black uppercase tracking-wider transition shrink-0 border flex items-center gap-2 {{ ($currentCategory && $currentCategory->id === $cat->id) ? 'bg-[#006d5b] text-white border-[#006d5b] shadow-md' : 'bg-white text-slate-600 border-slate-200 hover:border-[#006d5b] hover:text-[#006d5b]' }}">
                    @if($cat->image)
                    <img src="{{ asset('storage/'.$cat->image) }}" class="w-4 h-4 object-contain rounded-full">
                    @endif
                    <span>{{ $cat->name }}</span>
                </a>
                @endforeach
            </div>
        </div>

        <!-- Active Filter Badges & Reset -->
        @if($currentCategory || $searchTerm)
        <div class="mb-6 flex flex-wrap items-center gap-2">
            <span class="text-xs text-slate-500 font-bold">Filter Aktif:</span>
            @if($currentCategory)
            <a href="{{ route('catalog.index', array_filter(['q' => $searchTerm, 'sort' => $sort])) }}"
                class="inline-flex items-center gap-1.5 px-3 py-1 bg-teal-50 text-[#006d5b] border border-teal-200 rounded-full text-xs font-bold hover:bg-teal-100 transition">
                <span>Kategori: {{ $currentCategory->name }}</span>
                <i class="ti ti-x text-xs"></i>
            </a>
            @endif
            @if($searchTerm)
            <a href="{{ route('catalog.index', array_filter(['category' => request('category'), 'sort' => $sort])) }}"
                class="inline-flex items-center gap-1.5 px-3 py-1 bg-teal-50 text-[#006d5b] border border-teal-200 rounded-full text-xs font-bold hover:bg-teal-100 transition">
                <span>Pencarian: "{{ $searchTerm }}"</span>
                <i class="ti ti-x text-xs"></i>
            </a>
            @endif
            <a href="{{ route('catalog.index') }}" class="text-xs text-[#f53003] font-bold hover:underline ml-2">
                Hapus Semua Filter
            </a>
        </div>
        @endif

        <!-- Product Grid -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3 lg:gap-6 mb-12">
            @forelse ($products as $prod)
            <a href="{{ route('product.detail', $prod->slug) }}"
                class="product-card bg-white rounded-xl lg:rounded-2xl overflow-hidden shadow-sm hover:shadow-2xl transition duration-500 border border-slate-100 relative group flex flex-col justify-between">
                <div>
                    <div class="aspect-[4/5] bg-slate-100 relative overflow-hidden">
                        @php
                        $firstImg = is_array($prod->images) && count($prod->images) > 0 ? $prod->images[0] : null;
                        $imgSrc = $firstImg ? (Str::startsWith($firstImg, 'http') ? $firstImg :
                        asset('storage/'.$firstImg)) : asset('assets/img/elements/1.jpg');
                        @endphp
                        <img src="{{ $imgSrc }}" alt="{{ $prod->name }}"
                            class="w-full h-full object-cover group-hover:scale-110 transition duration-700">

                        <div class="absolute bottom-2 left-2 flex gap-1">
                            <div
                                class="bg-[#006d5b] text-white text-[8px] lg:text-[9px] px-2 py-0.5 rounded-lg font-black tracking-widest uppercase shadow-sm">
                                {{ $prod->category->name }}</div>
                        </div>
                    </div>
                    <div class="p-3 lg:p-4">
                        <h4
                            class="text-[10px] lg:text-[11px] font-bold line-clamp-2 mb-2 lg:mb-3 h-7 lg:h-8 leading-tight text-slate-700 group-hover:text-[#006d5b] transition">
                            {{ $prod->name }}</h4>
                        <div class="flex flex-col mb-2 lg:mb-3">
                            <span class="text-[#006d5b] font-black text-sm lg:text-base italic">Rp {{
                                number_format($prod->price, 0, ',', '.') }}</span>
                            @if($prod->old_price)
                            <span class="text-[8px] lg:text-[10px] text-slate-400 line-through">Rp {{
                                number_format($prod->old_price, 0, ',', '.') }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="p-3 lg:p-4 pt-0">
                    <div
                        class="flex items-center justify-between text-[8px] lg:text-[9px] text-slate-400 border-t pt-3 font-bold mb-3">
                        <div class="flex items-center gap-1">
                            <i class="ti ti-star-filled text-yellow-500"></i>
                            <span>{{ $prod->rating ?? '0' }}</span>
                        </div>
                        <span>{{ $prod->sold_count ?? '0' }} terjual</span>
                    </div>
                    <button onclick="event.preventDefault(); quickAdd({{ $prod->id }});"
                        class="w-full bg-slate-100 hover:bg-[#006d5b] text-slate-700 hover:text-white font-black py-2 rounded-lg text-[9px] uppercase tracking-wider transition flex items-center justify-center gap-1">
                        <i class="ti ti-shopping-cart"></i>
                        <span>+ Keranjang</span>
                    </button>
                </div>
            </a>
            @empty
            <div class="col-span-full bg-white p-12 rounded-3xl text-center border border-slate-100 shadow-sm">
                <div class="w-16 h-16 bg-slate-100 text-slate-400 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl">
                    <i class="ti ti-search-off"></i>
                </div>
                <h3 class="font-black text-lg text-slate-800 mb-1">Produk Tidak Ditemukan</h3>
                <p class="text-xs text-slate-500 mb-6 max-w-sm mx-auto">
                    Maaf, tidak ada produk yang cocok dengan pencarian atau filter yang Anda pilih saat ini.
                </p>
                <a href="{{ route('catalog.index') }}"
                    class="bg-[#006d5b] text-white font-black text-xs px-6 py-3 rounded-xl uppercase tracking-wider hover:bg-[#004d40] transition inline-block">
                    Lihat Semua Produk
                </a>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-8 flex justify-center">
            {{ $products->links() }}
        </div>

    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-white pt-12 lg:pt-16 pb-24 lg:pb-12 text-center lg:text-left mt-16">
        <div class="max-w-6xl mx-auto px-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">
            <div>
                <a href="/"
                    class="text-2xl lg:text-3xl font-black flex items-center justify-center lg:justify-start mb-6">
                    <span class="text-[#006d5b]">TOKO</span><span class="text-white">KITA</span>
                </a>
                <p class="text-slate-400 text-xs lg:text-sm leading-relaxed mb-6">Fashion terupdate dengan kualitas
                    premium. Belanja aman dan nyaman hanya di Toko Kita.</p>
                <div class="flex flex-col gap-4">
                    <h5 class="text-[10px] font-black uppercase text-[#006d5b] tracking-widest">Ikuti Kami</h5>
                    <div class="flex gap-4 justify-center lg:justify-start">
                        <a href="#"
                            class="w-9 h-9 bg-white/10 rounded-lg flex items-center justify-center hover:bg-[#006d5b] transition text-white border border-transparent"><i
                                class="ti ti-brand-facebook"></i></a>
                        <a href="#"
                            class="w-9 h-9 bg-white/10 rounded-lg flex items-center justify-center hover:bg-[#006d5b] transition text-white border border-transparent"><i
                                class="ti ti-brand-instagram"></i></a>
                        <a href="#"
                            class="w-9 h-9 bg-white/10 rounded-lg flex items-center justify-center hover:bg-[#006d5b] transition text-white border border-transparent"><i
                                class="ti ti-brand-tiktok"></i></a>
                    </div>
                </div>
            </div>

            <div class="hidden lg:block">
                <h4 class="font-bold text-lg mb-6 uppercase tracking-widest text-[#006d5b]">Informasi</h4>
                <ul class="flex flex-col gap-4 text-slate-400 text-sm font-medium">
                    <li><a href="#" class="hover:text-white transition">Tentang Kami</a></li>
                    <li><a href="#" class="hover:text-white transition">Cara Belanja</a></li>
                    <li><a href="#" class="hover:text-white transition">Syarat & Ketentuan</a></li>
                    <li><a href="#" class="hover:text-white transition">Kebijakan Privasi</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-bold text-lg lg:mb-6 mb-4 uppercase tracking-widest text-[#006d5b]">Pembayaran</h4>
                <div class="grid grid-cols-4 gap-2 mb-6 px-10 lg:px-0">
                    @foreach(['visa', 'mastercard', 'shopeepay', 'gopay', 'ovo', 'dana'] as $pay)
                    <div
                        class="bg-white/10 p-2 rounded-lg flex items-center justify-center grayscale hover:grayscale-0 transition cursor-pointer">
                        <i class="ti ti-credit-card text-white/50"></i>
                    </div>
                    @endforeach
                </div>
            </div>

            <div>
                <h4 class="font-bold text-lg mb-6 uppercase tracking-widest text-[#006d5b] lg:mb-6 mb-4">Kontak</h4>
                <div
                    class="flex flex-col gap-4 text-slate-400 text-xs lg:text-sm font-medium items-center lg:items-start">
                    <div class="flex gap-3 items-center">
                        <i class="ti ti-map-pin text-[#006d5b]"></i>
                        <span>Jl. Fashion No. 123, Jakarta Pusat</span>
                    </div>
                    <div class="flex gap-3 items-center">
                        <i class="ti ti-phone text-[#006d5b]"></i>
                        <span>+62 812 3456 7890</span>
                    </div>
                    <div class="flex gap-3 items-center">
                        <i class="ti ti-mail text-[#006d5b]"></i>
                        <span>support@tokokita.id</span>
                    </div>
                </div>
            </div>
        </div>
        <div
            class="max-w-6xl mx-auto px-4 mt-12 lg:mt-16 pt-8 border-t border-white/10 text-center text-slate-500 text-[9px] font-black tracking-widest uppercase">
            <p>&copy; 2026 TOKO KITA. SEMUA HAK DILINDUNGI UNDANG-UNDANG.</p>
        </div>
    </footer>

    <!-- Mobile Nav Wrapper -->
    @include('layouts.bottom-nav')

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function quickAdd(productId) {
            fetch('{{ route('cart.add') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    product_id: productId,
                    qty: 1
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: data.message,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.reload();
                    });
                }
            });
        }
    </script>
</body>

</html>
