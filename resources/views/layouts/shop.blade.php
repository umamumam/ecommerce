<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700;900&display=swap" rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/tabler-icons.css') }}" />

    <!-- Scripts / Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Public Sans', sans-serif; background-color: #fcfcfc; }
        .primary-teal { color: #006d5b; }
        .bg-primary-teal { background-color: #006d5b; }
        [x-cloak] { display: none !important; }
    </style>
    @stack('styles')
</head>

<body class="antialiased pb-20 lg:pb-0">

    <!-- Desktop Header -->
    <header class="hidden lg:block bg-white shadow-md sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between">
            <div class="flex items-center gap-12 flex-1">
                <a href="/" class="text-2xl font-black flex items-center">
                    <span class="text-[#006d5b]">TOKO</span><span class="text-[#f53003]">KITA</span>
                </a>
                <div class="relative flex-1 max-w-lg">
                    <input type="text" class="w-full bg-slate-100 border-none rounded-lg py-2.5 px-10 focus:ring-2 focus:ring-[#006d5b] text-sm" placeholder="Apa yang kamu cari hari ini?">
                    <i class="ti ti-search absolute left-3 top-3 text-slate-400"></i>
                </div>
            </div>

            <nav class="flex items-center gap-6 ml-8">
                <div class="flex items-center gap-6 border-r pr-6">
                    <a href="{{ auth()->check() ? (auth()->user()->role === 'admin' ? route('dashboard') : route('account')) : route('login') }}" class="flex flex-col items-center text-slate-700 hover:text-[#006d5b] transition group">
                        @auth
                        <img src="{{ auth()->user()->profile_photo ? asset('storage/'.auth()->user()->profile_photo) : asset('assets/img/avatars/1.png') }}" class="w-6 h-6 rounded-full object-cover group-hover:scale-110 transition border border-slate-100 shadow-sm">
                        @else
                        <i class="ti ti-user ti-sm group-hover:scale-110 transition"></i>
                        @endauth
                        <span class="text-[9px] font-bold uppercase mt-1">Akun</span>
                    </a>
                    <a href="{{ route('cart.index') }}" class="flex flex-col items-center text-slate-700 hover:text-[#006d5b] transition relative group">
                        <i class="ti ti-shopping-cart ti-sm group-hover:scale-110 transition"></i>
                        <span class="text-[9px] font-bold uppercase mt-1">Cart</span>
                        @if(count(session('cart', [])) > 0)
                        <span class="absolute -top-1.5 -right-2 bg-[#f53003] text-white text-[8px] w-4 h-4 flex items-center justify-center rounded-full border-2 border-white font-black animate-bounce">{{ count(session('cart', [])) }}</span>
                        @endif
                    </a>
                </div>
                @auth
                <a href="{{ route('dashboard') }}" class="btn-primary-teal px-5 py-2 rounded-lg text-xs bg-[#006d5b] text-white">DASHBOARD</a>
                @else
                <a href="{{ route('login') }}" class="btn-primary-teal px-5 py-2 rounded-lg text-xs bg-[#006d5b] text-white">MASUK / DAFTAR</a>
                @endauth
            </nav>
        </div>
    </header>

    <!-- Mobile Header -->
    <header class="lg:hidden bg-white p-4 sticky top-0 z-50 shadow-sm border-b border-slate-50">
        <div class="flex items-center justify-between mb-4">
            <a href="/" class="text-xl font-black flex items-center">
                <span class="text-[#006d5b]">TOKO</span><span class="text-[#f53003]">KITA</span>
            </a>
            <div class="flex items-center gap-5">
                <a href="{{ auth()->check() ? (auth()->user()->role === 'admin' ? route('dashboard') : route('account')) : route('login') }}" class="flex flex-col items-center text-slate-800">
                    @auth
                    <img src="{{ auth()->user()->profile_photo ? asset('storage/'.auth()->user()->profile_photo) : asset('assets/img/avatars/1.png') }}" class="w-7 h-7 rounded-full object-cover border border-slate-100 shadow-sm">
                    @else
                    <i class="ti ti-user text-xl"></i>
                    @endauth
                    <span class="text-[8px] font-bold uppercase mt-0.5">Akun</span>
                </a>
                <a href="{{ route('cart.index') }}" class="relative flex flex-col items-center text-slate-800">
                    <i class="ti ti-shopping-cart text-xl"></i>
                    <span class="text-[8px] font-bold uppercase">Cart</span>
                    @if(count(session('cart', [])) > 0)
                    <span class="absolute -top-1 -right-2 bg-[#f53003] text-white text-[8px] w-4 h-4 flex items-center justify-center rounded-full border border-white font-bold">{{ count(session('cart', [])) }}</span>
                    @endif
                </a>
            </div>
        </div>
        <form action="{{ route('catalog.index') }}" method="GET" class="relative">
            <input type="text" name="q" value="{{ request('q') }}"
                class="w-full bg-slate-100/90 border-none rounded-lg py-1.5 pl-9 pr-3 text-[11px] placeholder-slate-400 focus:ring-1 focus:ring-[#006d5b] h-9"
                placeholder="Cari fashion terbaru...">
            <button type="submit" class="absolute left-3 top-2.5 text-slate-400 hover:text-[#006d5b]">
                <i class="ti ti-search text-sm"></i>
            </button>
        </form>
    </header>

    <main>
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-white pt-12 lg:pt-16 pb-24 lg:pb-12 text-center lg:text-left mt-12">
        <div class="max-w-6xl mx-auto px-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">
            <div>
                <a href="/" class="text-2xl lg:text-3xl font-black flex items-center justify-center lg:justify-start mb-6">
                    <span class="text-[#006d5b]">TOKO</span><span class="text-white">KITA</span>
                </a>
                <p class="text-slate-400 text-xs lg:text-sm leading-relaxed mb-6">Fashion terupdate dengan kualitas premium. Belanja aman dan nyaman hanya di Toko Kita.</p>
                <div class="flex flex-col gap-4">
                    <h5 class="text-[10px] font-black uppercase text-[#006d5b] tracking-widest">Ikuti Kami</h5>
                    <div class="flex gap-4 justify-center lg:justify-start">
                        <a href="#" class="w-9 h-9 bg-white/10 rounded-lg flex items-center justify-center hover:bg-[#006d5b] transition text-white border border-transparent"><i class="ti ti-brand-facebook"></i></a>
                        <a href="#" class="w-9 h-9 bg-white/10 rounded-lg flex items-center justify-center hover:bg-[#006d5b] transition text-white border border-transparent"><i class="ti ti-brand-instagram"></i></a>
                        <a href="#" class="w-9 h-9 bg-white/10 rounded-lg flex items-center justify-center hover:bg-[#006d5b] transition text-white border border-transparent"><i class="ti ti-brand-tiktok"></i></a>
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
                <h4 class="font-bold text-lg lg:mb-6 mb-4 uppercase tracking-widest text-[#006d5b]">Partner Pembayaran</h4>
                <div class="grid grid-cols-4 gap-2 mb-6 px-10 lg:px-0 opacity-50">
                    <i class="ti ti-brand-visa text-2xl"></i>
                    <i class="ti ti-brand-mastercard text-2xl"></i>
                    <i class="ti ti-credit-card text-2xl"></i>
                    <i class="ti ti-building-bank text-2xl"></i>
                </div>
                <h4 class="font-bold text-lg lg:mb-6 mb-4 uppercase tracking-widest text-[#006d5b]">Partner Pengiriman</h4>
                <div class="flex flex-wrap gap-2 justify-center lg:justify-start">
                    @foreach(['JNE', 'J&T', 'SICEPAT', 'GOSEND'] as $ship)
                    <span class="bg-white/10 px-3 py-1 rounded text-[9px] font-black uppercase tracking-widest text-white/70 border border-white/5">{{ $ship }}</span>
                    @endforeach
                </div>
            </div>

            <div>
                <h4 class="font-bold text-lg mb-6 uppercase tracking-widest text-[#006d5b] lg:mb-6 mb-4">Kontak</h4>
                <div class="flex flex-col gap-4 text-slate-400 text-xs lg:text-sm font-medium items-center lg:items-start">
                    <div class="flex gap-3 items-center"><i class="ti ti-map-pin text-[#006d5b]"></i><span>Jl. Fashion No. 123, Jakarta Pusat</span></div>
                    <div class="flex gap-3 items-center"><i class="ti ti-phone text-[#006d5b]"></i><span>+62 812 3456 7890</span></div>
                    <div class="flex gap-3 items-center"><i class="ti ti-mail text-[#006d5b]"></i><span>support@tokokita.id</span></div>
                </div>
            </div>
        </div>
        <div class="max-w-6xl mx-auto px-4 mt-12 lg:mt-16 pt-8 border-t border-white/10 text-center text-slate-500 text-[9px] font-black tracking-widest uppercase">
            <p>&copy; 2026 TOKO KITA. SEMUA HAK DILINDUNGI UNDANG-UNDANG.</p>
        </div>
    </footer>

    <!-- Mobile Nav Wrapper -->
    @include('layouts.bottom-nav')

    <!-- jQuery & SweetAlert2 -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Global Flash Message Handler
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                Swal.fire({ icon: 'success', title: 'Berhasil!', text: "{{ session('success') }}", confirmButtonColor: '#006d5b', timer: 3000 });
            @endif
            @if(session('error'))
                Swal.fire({ icon: 'error', title: 'Gagal!', text: "{{ session('error') }}", confirmButtonColor: '#006d5b' });
            @endif
        });

        function quickAdd(productId) {
            $.ajax({
                url: '{{ route('cart.add') }}',
                method: 'POST',
                data: { _token: '{{ csrf_token() }}', product_id: productId, qty: 1 },
                success: function(res) {
                    Swal.fire({ icon: 'success', title: 'Berhasil!', text: res.message, showConfirmButton: false, timer: 1500 })
                    .then(() => window.location.reload());
                }
            });
        }
    </script>
    @stack('scripts')
</body>

</html>
