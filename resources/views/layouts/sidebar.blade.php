<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
  <div class="app-brand demo">
    <a href="{{ route('dashboard') }}" class="app-brand-link">
      <span class="app-brand-logo demo">
        <svg width="32" height="22" viewBox="0 0 32 22" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path fill-rule="evenodd" clip-rule="evenodd"
            d="M0.00172773 0V6.85398C0.00172773 6.85398 -0.133178 9.01207 1.98092 10.8388L13.6912 21.9964L19.7809 21.9181L18.8042 9.88248L16.4951 7.17289L9.23799 0H0.00172773Z"
            fill="#7367F0" />
          <path opacity="0.06" fill-rule="evenodd" clip-rule="evenodd"
            d="M7.69824 16.4364L12.5199 3.23696L16.5541 7.25596L7.69824 16.4364Z" fill="#161616" />
          <path opacity="0.06" fill-rule="evenodd" clip-rule="evenodd"
            d="M8.07751 15.9175L13.9419 4.63989L16.5849 7.28475L8.07751 15.9175Z" fill="#161616" />
          <path fill-rule="evenodd" clip-rule="evenodd"
            d="M7.77295 16.3566L23.6563 0H32V6.88383C32 6.88383 31.8262 9.17836 30.6591 10.4057L19.7824 22H13.6938L7.77295 16.3566Z"
            fill="#7367F0" />
        </svg>
      </span>
      <span class="app-brand-text demo menu-text fw-bold">Vuexy</span>
    </a>

    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
      <i class="ti menu-toggle-icon d-none d-xl-block align-middle"></i>
      <i class="ti ti-x d-block d-xl-none ti-md align-middle"></i>
    </a>
  </div>

  <div class="menu-inner-shadow"></div>

  <ul class="menu-inner py-1">
    <!-- Dashboards -->
    <li class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
      <a href="{{ route('dashboard') }}" class="menu-link">
        <i class="menu-icon tf-icons ti ti-smart-home"></i>
        <div data-i18n="Dashboard">Dashboard</div>
      </a>
    </li>

    <li class="menu-header small">
      <span class="menu-header-text" data-i18n="Sistem">Sistem</span>
    </li>
    <li class="menu-item {{ request()->is('admin/orders*') ? 'active' : '' }}">
      <a href="{{ route('admin.orders.index') }}" class="menu-link">
        <i class="menu-icon tf-icons ti ti-shopping-cart"></i>
        <div data-i18n="Manajemen Order">Manajemen Order</div>
      </a>
    </li>
    <li class="menu-item {{ request()->is('admin/tools*') ? 'active open' : '' }}">
      <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons ti ti-truck"></i>
        <div data-i18n="Layanan Paket">Layanan Paket</div>
      </a>
      <ul class="menu-sub">
        <li class="menu-item {{ request()->routeIs('admin.tools.ongkir') ? 'active' : '' }}">
          <a href="{{ route('admin.tools.ongkir') }}" class="menu-link">
            <div data-i18n="Cek Ongkir">Cek Ongkir</div>
          </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.tools.resi') ? 'active' : '' }}">
          <a href="{{ route('admin.tools.resi') }}" class="menu-link">
            <div data-i18n="Cek Resi">Cek Resi</div>
          </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.tools.scan') ? 'active' : '' }}">
          <a href="{{ route('admin.tools.scan') }}" class="menu-link">
            <div data-i18n="Scan Out Station">Scan Out Station</div>
          </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.tools.webhooks') ? 'active' : '' }}">
          <a href="{{ route('admin.tools.webhooks') }}" class="menu-link">
            <div data-i18n="Webhook Logs">Webhook Logs</div>
          </a>
        </li>
        <li class="menu-item {{ request()->routeIs('admin.couriers.index') ? 'active' : '' }}">
          <a href="{{ route('admin.couriers.index') }}" class="menu-link">
            <div data-i18n="Manajemen Kurir">Manajemen Kurir</div>
          </a>
        </li>
      </ul>
    </li>

    <li class="menu-item {{ request()->routeIs('products.index') ? 'active' : '' }}">
      <a href="{{ route('products.index') }}" class="menu-link">
        <i class="menu-icon tf-icons ti ti-box"></i>
        <div data-i18n="Daftar Produk">Daftar Produk</div>
      </a>
    </li>

    <!-- Management -->
    <li class="menu-header small">
      <span class="menu-header-text" data-i18n="Manajemen">Manajemen</span>
    </li>
    <li class="menu-item {{ request()->routeIs('users.index') ? 'active' : '' }}">
      <a href="{{ route('users.index') }}" class="menu-link">
        <i class="menu-icon tf-icons ti ti-users"></i>
        <div data-i18n="User Management">User Management</div>
      </a>
    </li>
    <li class="menu-item {{ request()->routeIs('categories.index') ? 'active' : '' }}">
      <a href="{{ route('categories.index') }}" class="menu-link">
        <i class="menu-icon tf-icons ti ti-category"></i>
        <div data-i18n="Kategori Produk">Kategori Produk</div>
      </a>
    </li>
    <!-- Development -->
    <li class="menu-header small">
      <span class="menu-header-text" data-i18n="Development">Development</span>
    </li>
    <li class="menu-item {{ request()->routeIs('admin.integration.index') ? 'active' : '' }}">
      <a href="{{ route('admin.integration.index') }}" class="menu-link">
        <i class="menu-icon tf-icons ti ti-settings-automation"></i>
        <div data-i18n="Integrasi API">Integrasi API</div>
      </a>
    </li>
    <li class="menu-item {{ request()->routeIs('admin.xendit.index') ? 'active' : '' }}">
      <a href="{{ route('admin.xendit.index') }}" class="menu-link">
        <i class="menu-icon tf-icons ti ti-credit-card"></i>
        <div data-i18n="API Xendit">API Xendit</div>
      </a>
    </li>
  </ul>
</aside>