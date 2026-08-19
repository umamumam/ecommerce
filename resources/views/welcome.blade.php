<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Landing Page | {{ config('app.name', 'Laravel') }}</title>

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

        .border-primary-teal {
            border-color: #006d5b;
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

        [x-cloak] {
            display: none !important;
        }

        .slider-dot.active {
            width: 1.5rem;
            background-color: #006d5b;
        }

        @keyframes marquee {
            0% {
                transform: translateX(0%);
            }
            100% {
                transform: translateX(-50%);
            }
        }

        .marquee-track {
            display: flex;
            width: max-content;
            animation: marquee 26s linear infinite;
        }

        .marquee-track:hover {
            animation-play-state: paused;
        }
    </style>
</head>

<body class="antialiased pb-20 lg:pb-0" x-data="globalState()">

    <!-- Desktop Header -->
    <header class="hidden lg:block bg-white shadow-md sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between">
            <div class="flex items-center gap-12 flex-1">
                <a href="/" class="text-2xl font-black flex items-center">
                    <span class="text-[#006d5b]">TOKO</span><span class="text-[#f53003]">KITA</span>
                </a>
                <form action="{{ route('catalog.index') }}" method="GET" class="relative flex-1 max-w-lg">
                    <input type="text" name="q"
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
            <input type="text" name="q"
                class="w-full bg-slate-100/90 border-none rounded-lg py-1.5 pl-9 pr-3 text-[11px] placeholder-slate-400 focus:ring-1 focus:ring-[#006d5b] h-9"
                placeholder="Cari fashion terbaru...">
            <button type="submit" class="absolute left-3 top-2.5 text-slate-400 hover:text-[#006d5b]">
                <i class="ti ti-search text-sm"></i>
            </button>
        </form>
    </header>

    <main class="max-w-6xl mx-auto px-4 lg:px-0 py-6">

        <!-- Hero Slider (Alpine.js) -->
        <section class="mb-10 lg:rounded-3xl overflow-hidden relative shadow-2xl animate-fade-up" x-data="slider()">
            <div class="relative aspect-[4/3] lg:aspect-[21/8]">
                <!-- Slides -->
                <template x-for="(slide, index) in slides" :key="index">
                    <div x-show="active === index" x-transition:enter="transition ease-out duration-1000"
                        x-transition:enter-start="opacity-0 lg:scale-105"
                        x-transition:enter-end="opacity-100 lg:scale-100"
                        x-transition:leave="transition ease-in duration-1000" x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0" class="absolute inset-0">
                        <img :src="slide.image" class="w-full h-full object-cover object-center">
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/20 to-transparent flex flex-col justify-center px-8 lg:px-20 text-white">
                            <div class="max-w-[90%] lg:max-w-lg">
                                <span
                                    class="bg-[#f53003] text-white text-[9px] lg:text-[10px] font-bold px-3 py-1 lg:px-4 lg:py-1.5 rounded-full uppercase tracking-[0.2em] mb-4 lg:mb-6 inline-block"
                                    x-text="slide.badge"></span>
                                <h2
                                    class="text-2xl lg:text-6xl font-black mb-3 lg:mb-4 leading-tight lg:leading-[1.1] uppercase drop-shadow-2xl">
                                    <span x-html="slide.title"></span>
                                </h2>
                                <p class="text-xs lg:text-xl mb-6 lg:mb-8 text-white/80 max-w-[280px] lg:max-w-sm"
                                    x-text="slide.desc"></p>
                                <a href="#"
                                    class="bg-[#006d5b] hover:bg-[#004d40] text-white font-black py-3 lg:py-4 px-8 lg:px-10 rounded-xl w-fit transition transform hover:scale-105 inline-block text-xs lg:text-sm tracking-wider shadow-xl">ORDER
                                    SEKARANG</a>
                            </div>
                        </div>
                    </div>
                </template>

                <!-- Dots -->
                <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2 z-10">
                    <template x-for="(slide, index) in slides" :key="index">
                        <button @click="active = index; stopAutoplay()"
                            class="slider-dot w-2 h-2 rounded-full bg-white/50 transition-all duration-300"
                            :class="{ 'active': active === index }"></button>
                    </template>
                </div>
            </div>
        </section>

        <!-- Limited Offer Section (Alpine.js) -->
        <section class="mb-12 bg-[#fff2f2] p-6 lg:p-8 rounded-3xl animate-fade-up" style="animation-delay: 0.1s"
            x-data="countdown()">
            <div class="flex flex-col lg:flex-row items-center justify-between mb-8 gap-4 text-center lg:text-left">
                <div class="flex items-center gap-4">
                    <div
                        class="w-12 h-12 bg-[#f53003] text-white rounded-2xl flex items-center justify-center animate-bounce border-4 border-white shadow-lg shrink-0">
                        <i class="ti ti-bolt text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="font-black text-xl lg:text-2xl uppercase tracking-tight">PENAWARAN TERBATAS</h3>
                        <p class="text-[10px] lg:text-xs text-slate-500 font-bold">Berakhir dalam:</p>
                    </div>
                </div>
                <div class="flex gap-2 items-center">
                    <div class="bg-black text-white px-3 py-2 rounded-lg text-center min-w-[50px]">
                        <div class="text-sm lg:text-base font-black" x-text="time.h">02</div>
                        <div class="text-[7px] lg:text-[8px] uppercase font-bold">Jam</div>
                    </div>
                    <div class="text-black font-black text-xl">:</div>
                    <div class="bg-black text-white px-3 py-2 rounded-lg text-center min-w-[50px]">
                        <div class="text-sm lg:text-base font-black" x-text="time.m">45</div>
                        <div class="text-[7px] lg:text-[8px] uppercase font-bold">Men</div>
                    </div>
                    <div class="text-black font-black text-xl">:</div>
                    <div class="bg-black text-white px-3 py-2 rounded-lg text-center min-w-[50px]">
                        <div class="text-sm lg:text-base font-black" x-text="time.s">12</div>
                        <div class="text-[7px] lg:text-[8px] uppercase font-bold">Det</div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                @for ($i = 1; $i <= 4; $i++) <div
                    class="bg-white p-3 rounded-2xl shadow-sm hover:shadow-xl transition group">
                    <div class="aspect-square bg-slate-100 rounded-xl overflow-hidden mb-3 relative">
                        <img src="https://picsum.photos/400/400?fashion={{ $i + 10 }}"
                            class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                        <div
                            class="absolute top-2 right-2 bg-[#f53003] text-white font-black text-[9px] px-2 py-1 rounded-lg shadow-sm">
                            -40%</div>
                    </div>
                    <h4 class="text-[10px] lg:text-xs font-bold line-clamp-1 mb-2">Exclusive Piece No {{ $i }}</h4>
                    <div class="flex flex-col mb-2">
                        <span class="text-[#f53003] font-black text-xs lg:text-sm">Rp {{ number_format(rand(100000,
                            200000), 0, ',', '.') }}</span>
                        <span class="text-[9px] text-slate-400 line-through">Rp {{ number_format(rand(250000, 400000),
                            0, ',', '.') }}</span>
                    </div>
                    <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                        <div class="bg-[#f53003] h-full transition-all duration-1000"
                            :style="'width: ' + progress + '%'"></div>
                    </div>
                    <div class="text-[8px] mt-1 text-slate-500 font-bold">Sisa Stok Terbatas!</div>
            </div>
            @endfor
            </div>
        </section>

        <!-- Service Highlights -->
        <section class="mb-12 grid grid-cols-2 lg:grid-cols-4 gap-3 lg:gap-4 animate-fade-up"
            style="animation-delay: 0.15s">
            <div
                class="service-card flex items-center gap-3 p-3.5 lg:p-5 bg-white border border-slate-100 rounded-2xl shadow-sm transition duration-300">
                <div class="w-9 h-9 lg:w-12 lg:h-12 bg-blue-50 text-blue-500 rounded-xl flex items-center justify-center shrink-0">
                    <i class="ti ti-truck text-lg lg:text-2xl"></i>
                </div>
                <div>
                    <h5 class="text-[11px] lg:text-xs font-black uppercase text-slate-700">Gratis Ongkir</h5>
                    <p class="text-[9px] lg:text-[10px] text-slate-500 line-clamp-1">Belanja hemat ke seluruh Indo</p>
                </div>
            </div>
            <div
                class="service-card flex items-center gap-3 p-3.5 lg:p-5 bg-white border border-slate-100 rounded-2xl shadow-sm transition duration-300">
                <div class="w-9 h-9 lg:w-12 lg:h-12 bg-green-50 text-green-500 rounded-xl flex items-center justify-center shrink-0">
                    <i class="ti ti-shield-check text-lg lg:text-2xl"></i>
                </div>
                <div>
                    <h5 class="text-[11px] lg:text-xs font-black uppercase text-slate-700">100% Original</h5>
                    <p class="text-[9px] lg:text-[10px] text-slate-500 line-clamp-1">Jaminan produk berkualitas</p>
                </div>
            </div>
            <div
                class="service-card flex items-center gap-3 p-3.5 lg:p-5 bg-white border border-slate-100 rounded-2xl shadow-sm transition duration-300">
                <div
                    class="w-9 h-9 lg:w-12 lg:h-12 bg-orange-50 text-orange-500 rounded-xl flex items-center justify-center shrink-0">
                    <i class="ti ti-headset text-lg lg:text-2xl"></i>
                </div>
                <div>
                    <h5 class="text-[11px] lg:text-xs font-black uppercase text-slate-700">Layanan 24/7</h5>
                    <p class="text-[9px] lg:text-[10px] text-slate-500 line-clamp-1">Bantuan CS ramah & cepat</p>
                </div>
            </div>
            <div
                class="service-card flex items-center gap-3 p-3.5 lg:p-5 bg-white border border-slate-100 rounded-2xl shadow-sm transition duration-300">
                <div
                    class="w-9 h-9 lg:w-12 lg:h-12 bg-purple-50 text-purple-500 rounded-xl flex items-center justify-center shrink-0">
                    <i class="ti ti-lock text-lg lg:text-2xl"></i>
                </div>
                <div>
                    <h5 class="text-[11px] lg:text-xs font-black uppercase text-slate-700">Bayar Aman</h5>
                    <p class="text-[9px] lg:text-[10px] text-slate-500 line-clamp-1">Privasi & data terjaga aman</p>
                </div>
            </div>
        </section>

        <!-- Categories -->
        <section class="mb-12 animate-fade-up" style="animation-delay: 0.2s">
            <div class="flex items-center justify-between mb-6">
                <h3 class="font-black text-base lg:text-lg uppercase tracking-widest border-l-4 border-[#006d5b] pl-4">
                    KATEGORI PRODUK</h3>
                <a href="{{ route('catalog.index') }}"
                    class="text-[#006d5b] text-[10px] lg:text-xs font-black hover:underline uppercase tracking-tighter">Lihat
                    Semua</a>
            </div>
            <div class="categories-scroll gap-6 pb-4">
                @forelse($categories as $cat)
                <a href="{{ route('catalog.index', ['category' => $cat->slug]) }}" class="flex flex-col items-center gap-2 lg:gap-3 shrink-0 group cursor-pointer">
                    <div
                        class="w-14 h-14 lg:w-24 lg:h-24 bg-white rounded-2xl lg:rounded-3xl shadow-sm flex items-center justify-center border border-slate-200/80 transition-all duration-300 transform group-hover:-translate-y-2 group-hover:shadow-xl group-hover:shadow-[#006d5b]/15 group-hover:border-[#006d5b] active:scale-95 overflow-hidden">
                        @if($cat->image)
                        <img src="{{ asset('storage/'.$cat->image) }}"
                            class="w-8 h-8 lg:w-12 lg:h-12 object-contain group-hover:scale-110 transition-transform duration-300 ease-out">
                        @else
                        <i
                            class="ti ti-category text-2xl lg:text-3xl text-slate-400 group-hover:text-[#006d5b] group-hover:scale-110 transition-all duration-300"></i>
                        @endif
                    </div>
                    <span
                        class="text-[8px] lg:text-[10px] font-black uppercase tracking-widest text-slate-600 group-hover:text-[#006d5b] transition-colors duration-300">{{
                        $cat->name }}</span>
                </a>
                @empty
                <p class="text-xs text-slate-400 italic">Belum ada kategori aktif.</p>
                @endforelse
            </div>
        </section>

        <!-- Product Grid -->
        <section class="mb-16 animate-fade-up" style="animation-delay: 0.3s">
            <div class="flex flex-col items-center mb-10 text-center">
                <h3 class="font-black text-xl lg:text-3xl uppercase tracking-tighter mb-2">REKOMENDASI TERBAIK</h3>
                <div class="w-16 lg:w-20 h-1.5 bg-[#006d5b] rounded-full"></div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3 lg:gap-8">
                @forelse ($products as $prod)
                <a href="{{ route('product.detail', $prod->slug) }}"
                    class="product-card bg-white rounded-xl lg:rounded-2xl overflow-hidden shadow-sm hover:shadow-2xl transition duration-500 border border-slate-50 relative group">
                    <div class="aspect-[4/5] bg-slate-100 relative overflow-hidden">
                        @php
                        $firstImg = is_array($prod->images) && count($prod->images) > 0 ? $prod->images[0] : null;
                        $imgSrc = $firstImg ? (Str::startsWith($firstImg, 'http') ? $firstImg :
                        asset('storage/'.$firstImg)) : asset('assets/img/elements/1.jpg');
                        @endphp
                        <img src="{{ $imgSrc }}" alt="{{ $prod->name }}"
                            class="w-full h-full object-cover group-hover:scale-110 transition duration-700">

                        <!-- Side Actions -->
                        <div class="product-actions">
                            <button class="action-btn">
                                <i class="ti ti-heart ti-xs"></i>
                            </button>
                            <button class="action-btn">
                                <i class="ti ti-eye ti-xs"></i>
                            </button>
                            <button class="action-btn" @click.prevent="quickAdd({{ $prod->id }})">
                                <i class="ti ti-shopping-cart ti-xs"></i>
                            </button>
                        </div>

                        <div class="absolute bottom-2 left-2 flex gap-1">
                            <div
                                class="bg-[#006d5b] text-white text-[8px] lg:text-[9px] px-2 py-0.5 rounded-lg font-black tracking-widest uppercase">
                                {{ $prod->category->name }}</div>
                        </div>
                    </div>
                    <div class="p-3 lg:p-4">
                        <h4
                            class="text-[10px] lg:text-[11px] font-bold line-clamp-2 mb-2 lg:mb-3 h-7 lg:h-8 leading-tight text-slate-700">
                            {{ $prod->name }}</h4>
                        <div class="flex flex-col mb-2 lg:mb-3">
                            <span class="text-[#006d5b] font-black text-sm lg:text-base italic">Rp {{
                                number_format($prod->price, 0, ',', '.') }}</span>
                            @if($prod->old_price)
                            <span class="text-[8px] lg:text-[10px] text-slate-400 line-through">Rp {{
                                number_format($prod->old_price, 0, ',', '.') }}</span>
                            @endif
                        </div>
                        <div
                            class="flex items-center justify-between text-[8px] lg:text-[9px] text-slate-400 border-t pt-3 font-bold">
                            <div class="flex items-center gap-1">
                                <i class="ti ti-star-filled text-yellow-500"></i>
                                <span>{{ $prod->rating ?? '0' }}</span>
                            </div>
                            <span>{{ $prod->sold_count ?? '0' }} terjual</span>
                        </div>
                    </div>
                </a>
                @empty
                <div class="col-span-full text-center py-10">
                    <p class="text-slate-400">Belum ada produk yang ditampilkan.</p>
                </div>
                @endforelse
            </div>

            <div class="flex justify-center mt-12">
                <a href="{{ route('catalog.index') }}"
                    class="bg-white border-2 border-[#006d5b] text-[#006d5b] px-6 lg:px-10 py-3 lg:py-4 rounded-xl lg:rounded-2xl font-black text-[10px] lg:text-sm uppercase tracking-widest hover:bg-[#006d5b] hover:text-white transition duration-300 transform hover:-translate-y-1 shadow-lg inline-block">Lihat
                    Semua Koleksi</a>
            </div>
        </section>

        <!-- Shipping Partners Section (Moving Slideshow) -->
        <section class="mb-14 bg-white p-6 lg:p-8 rounded-3xl border border-slate-100 shadow-sm overflow-hidden animate-fade-up">
            <div class="flex flex-col items-center mb-6 text-center">
                <span class="text-[10px] font-black text-[#006d5b] uppercase tracking-widest mb-1">Pengiriman Cepat &amp; Aman</span>
                <h3 class="font-black text-base lg:text-xl uppercase tracking-tight text-slate-800">Didukung Ekspedisi Terpercaya</h3>
                <p class="text-xs text-slate-400 mt-1 max-w-md">Menjangkau seluruh pelosok Indonesia dengan integrasi kurir instan, reguler, express, dan kargo</p>
            </div>

            <!-- Continuous Infinite Moving Carousel (Right to Left) -->
            <div class="relative w-full overflow-hidden py-2">
                <!-- Soft Gradient Fade Overlays -->
                <div class="pointer-events-none absolute inset-y-0 left-0 w-16 lg:w-24 bg-gradient-to-r from-white to-transparent z-10"></div>
                <div class="pointer-events-none absolute inset-y-0 right-0 w-16 lg:w-24 bg-gradient-to-l from-white to-transparent z-10"></div>

                <div class="marquee-track flex items-center">
                    <!-- First Set of Courier Logos -->
                    @foreach(['jne', 'jnt', 'pos', 'sicepat', 'anteraja', 'ninja', 'tiki', 'lion', 'sap', 'wahana', 'ide', 'paxel', 'gosend', 'grab'] as $cur)
                    <div class="w-32 lg:w-40 h-14 lg:h-16 bg-slate-50 hover:bg-white border border-slate-100 hover:border-[#006d5b]/30 rounded-2xl flex items-center justify-center p-3 transition-all shadow-2xs hover:shadow-md shrink-0 mx-2 group">
                        <img src="{{ asset('assets/img/couriers/'.$cur.'.webp') }}" 
                             class="max-h-7 lg:max-h-8 max-w-full object-contain filter group-hover:scale-110 transition-transform duration-300" 
                             alt="{{ strtoupper($cur) }}"
                             onerror="this.src='{{ asset('assets/img/couriers/'.$cur.'.svg') }}'">
                    </div>
                    @endforeach

                    <!-- Duplicate Set for Seamless Infinite Loop -->
                    @foreach(['jne', 'jnt', 'pos', 'sicepat', 'anteraja', 'ninja', 'tiki', 'lion', 'sap', 'wahana', 'ide', 'paxel', 'gosend', 'grab'] as $cur)
                    <div class="w-32 lg:w-40 h-14 lg:h-16 bg-slate-50 hover:bg-white border border-slate-100 hover:border-[#006d5b]/30 rounded-2xl flex items-center justify-center p-3 transition-all shadow-2xs hover:shadow-md shrink-0 mx-2 group">
                        <img src="{{ asset('assets/img/couriers/'.$cur.'.webp') }}" 
                             class="max-h-7 lg:max-h-8 max-w-full object-contain filter group-hover:scale-110 transition-transform duration-300" 
                             alt="{{ strtoupper($cur) }}"
                             onerror="this.src='{{ asset('assets/img/couriers/'.$cur.'.svg') }}'">
                    </div>
                    @endforeach
                </div>
            </div>
        <!-- Payment Partners Section -->
        <section class="mb-14 bg-white p-6 lg:p-8 rounded-3xl border border-slate-100 shadow-sm animate-fade-up">
            <div class="flex flex-col items-center mb-6 text-center">
                <span class="text-[10px] font-black text-[#006d5b] uppercase tracking-widest mb-1">Transaksi Mudah &amp; Terpercaya</span>
                <h3 class="font-black text-base lg:text-xl uppercase tracking-tight text-slate-800">Metode Pembayaran Lengkap</h3>
                <p class="text-xs text-slate-400 mt-1 max-w-md">Mendukung QRIS, Virtual Account Bank Terbesar di Indonesia, E-Wallet, dan Kartu Kredit dengan keamanan terenkripsi</p>
            </div>
            <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-11 gap-2.5 items-center justify-center">
                @foreach(['qris', 'bca', 'mandiri', 'bni', 'bri', 'gopay', 'ovo', 'dana', 'shopeepay', 'visa', 'mastercard'] as $pay)
                <div class="bg-slate-50 hover:bg-white border border-slate-100 hover:border-[#006d5b]/30 p-2 rounded-xl flex items-center justify-center h-12 transition shadow-2xs hover:shadow-md group">
                    <img src="{{ asset('assets/img/payments/'.$pay.'.svg') }}" 
                         class="max-h-6 max-w-full object-contain filter group-hover:scale-110 transition-transform duration-300" 
                         alt="{{ strtoupper($pay) }}">
                </div>
                @endforeach
            </div>
        </section>

    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-white pt-12 lg:pt-16 pb-24 lg:pb-12 text-center lg:text-left">
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
                <div class="grid grid-cols-4 gap-1.5 mb-6">
                    @foreach(['qris', 'bca', 'mandiri', 'bni', 'bri', 'gopay', 'ovo', 'dana', 'shopeepay', 'visa', 'mastercard'] as $pay)
                    <div class="bg-white p-1 rounded-lg flex items-center justify-center h-7 shadow-xs hover:scale-105 transition-transform">
                        <img src="{{ asset('assets/img/payments/'.$pay.'.svg') }}" class="max-h-4 max-w-full object-contain" alt="{{ strtoupper($pay) }}">
                    </div>
                    @endforeach
                </div>
                <h4 class="font-bold text-lg lg:mb-6 mb-4 uppercase tracking-widest text-[#006d5b]">Partner Pengiriman
                </h4>
                <div class="grid grid-cols-3 gap-1.5 justify-center lg:justify-start">
                    @foreach(['jne', 'jnt', 'pos', 'sicepat', 'anteraja', 'ninja'] as $c)
                    <div class="bg-white p-1 rounded-lg flex items-center justify-center h-7 shadow-xs hover:scale-105 transition-transform">
                        <img src="{{ asset('assets/img/couriers/'.$c.'.webp') }}" class="max-h-4 max-w-full object-contain" alt="{{ strtoupper($c) }}" onerror="this.src='{{ asset('assets/img/couriers/'.$c.'.svg') }}'">
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

    <!-- Floating CS Button -->
    <a href="https://wa.me/6281234567890" target="_blank" class="whatsapp-float group">
        <i class="ti ti-brand-whatsapp text-3xl"></i>
        <span
            class="absolute right-16 bg-white text-slate-800 text-[10px] font-bold px-3 py-1.5 rounded-lg shadow-xl opacity-0 group-hover:opacity-100 transition whitespace-nowrap hidden lg:block">Chat
            Customer Service</span>
    </a>

    <script>
        function globalState() {
            return {
                // Any global state if needed
            }
        }

        function slider() {
            return {
                active: 0,
                interval: null,
                slides: [
                    {
                        image: 'https://images.unsplash.com/photo-1483985988355-763728e1935b?auto=format&fit=crop&q=80&w=2000',
                        badge: 'EKSKLUSIF',
                        title: 'GAYA KEREN<br><span class="text-white">HARGA TEMEN</span>',
                        desc: 'Temukan koleksi fashion terupdate dengan kualitas premium hanya di Toko Kita.'
                    },
                    {
                        image: 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?auto=format&fit=crop&q=80&w=2000',
                        badge: 'LIMITED EDITION',
                        title: 'TAMPIL BEDA<br><span class="text-white">SETIAP HARI</span>',
                        desc: 'Koleksi desainer pilihan untuk kamu yang ingin tampil unik dan berkelas.'
                    },
                    {
                        image: 'https://images.unsplash.com/photo-1445205170230-053b830c6050?auto=format&fit=crop&q=80&w=2000',
                        badge: 'BIG SALE',
                        title: 'HEMAT HABIS<br><span class="text-white">HINGGA 70%</span>',
                        desc: 'Promo gila-gilaan akhir bulan. Jangan sampai kehabisan stok barang impianmu!'
                    }
                ],
                startAutoplay() {
                    this.interval = setInterval(() => {
                        this.active = (this.active + 1) % this.slides.length;
                    }, 7000);
                },
                stopAutoplay() {
                    if (this.interval) clearInterval(this.interval);
                },
                init() {
                    this.startAutoplay();
                }
            }
        }

        function countdown() {
            return {
                time: { h: '00', m: '00', s: '00' },
                progress: 80,
                secondsLeft: 7200 + Math.floor(Math.random() * 3600), // ~2-3 hours
                updateTimer() {
                    const h = Math.floor(this.secondsLeft / 3600);
                    const m = Math.floor((this.secondsLeft % 3600) / 60);
                    const s = this.secondsLeft % 60;
                    this.time = {
                        h: h.toString().padStart(2, '0'),
                        m: m.toString().padStart(2, '0'),
                        s: s.toString().padStart(2, '0')
                    };
                },
                init() {
                    this.updateTimer();
                    setInterval(() => {
                        if (this.secondsLeft > 0) {
                            this.secondsLeft--;
                            this.updateTimer();
                            // Slowly decrease progress bar
                            if (this.secondsLeft % 10 === 0 && this.progress > 5) {
                                this.progress -= 0.15;
                            }
                        }
                    }, 1000);
                }
            }
        }
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

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    confirmButtonColor: '#006d5b',
                    timer: 3000
                });
            @endif
        });
    </script>
</body>

</html>