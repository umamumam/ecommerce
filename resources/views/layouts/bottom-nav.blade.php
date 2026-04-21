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
        <a href="#" class="bottom-nav-item-center">
            <i class="ti ti-shopping-cart"></i>
        </a>
    </div>

    <a href="#" class="bottom-nav-item">
        <i class="ti ti-receipt ti-sm mb-1"></i>
        <span>Pesanan</span>
    </a>
    <a href="{{ route('login') }}" class="bottom-nav-item {{ Request::is('login') || Request::is('register') ? 'active' : '' }}">
        <i class="ti ti-user ti-sm mb-1"></i>
        <span>Akun</span>
    </a>
</nav>
