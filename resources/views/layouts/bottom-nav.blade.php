<nav class="bottom-nav lg:hidden">
    <a href="{{ url('/') }}" class="bottom-nav-item {{ Request::is('/') ? 'active' : '' }}">
        <i class="ti ti-home ti-sm mb-1"></i>
        <span>Beranda</span>
    </a>
    <a href="#" class="bottom-nav-item">
        <i class="ti ti-category ti-sm mb-1"></i>
        <span>Kategori</span>
    </a>
    
    <div class="relative flex justify-center">
        <a href="{{ route('cart.index') }}" class="bottom-nav-item-center relative">
            <i class="ti ti-shopping-cart"></i>
            @if(count(session('cart', [])) > 0)
            <span class="absolute -top-1 -right-1 bg-[#f53003] text-white text-[8px] w-4 h-4 flex items-center justify-center rounded-full border border-white font-bold">{{ count(session('cart', [])) }}</span>
            @endif
        </a>
    </div>

    <a href="#" class="bottom-nav-item">
        <i class="ti ti-receipt ti-sm mb-1"></i>
        <span>Pesanan</span>
    </a>
    <a href="{{ auth()->check() ? (auth()->user()->role === 'admin' ? route('dashboard') : route('account')) : route('login') }}" 
       class="bottom-nav-item {{ Request::is('account') || Request::is('login') || Request::is('register') || Request::is('dashboard') ? 'active' : '' }}">
        @auth
            <img src="{{ auth()->user()->profile_photo ? asset('storage/'.auth()->user()->profile_photo) : asset('assets/img/avatars/1.png') }}" class="w-6 h-6 rounded-full object-cover mb-1 border border-slate-100 shadow-sm">
        @else
            <i class="ti ti-user ti-sm mb-1"></i>
        @endauth
        <span>Akun</span>
    </a>
</nav>
