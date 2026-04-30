<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Akun Saya | {{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700;900&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/tabler-icons.css') }}" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Public Sans', sans-serif; background-color: #f7f9fc; }
        .premium-header {
            background: linear-gradient(135deg, #006d5b 0%, #10b981 100%);
            height: 180px;
            border-bottom-left-radius: 40px;
            border-bottom-right-radius: 40px;
            position: relative;
        }
        .profile-container { margin-top: -60px; position: relative; z-index: 10; }
        .menu-card {
            background: white; border-radius: 20px; padding: 16px;
            display: flex; align-items: center; gap: 14px; margin-bottom: 12px;
            transition: all 0.2s; border: 1px solid rgba(0,0,0,0.03);
            text-decoration: none;
        }
        .menu-card:active { transform: scale(0.98); background-color: #f8fafc; }
        .icon-wrapper {
            width: 40px; height: 40px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px; flex-shrink: 0;
        }
        .order-badge {
            position: absolute; top: -5px; right: -5px;
            background: #fe2c22; color: white; font-size: 9px;
            font-weight: 800; width: 16px; height: 16px;
            border-radius: 50%; display: flex; align-items: center;
            justify-content: center; border: 2px solid white;
        }
    </style>
</head>
<body class="antialiased pb-28">

    <div class="premium-header">
        <div class="max-w-[700px] mx-auto flex items-center justify-between px-6 pt-8 text-white">
            <div class="flex-1 flex justify-start">
                <a href="{{ url('/') }}" class="w-9 h-9 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-md hover:bg-white/30 transition">
                    <i class="ti ti-home text-lg"></i>
                </a>
            </div>
            <h1 class="text-lg font-black tracking-tighter uppercase text-center flex-[2]">Profil Saya</h1>
            <div class="flex-1 flex justify-end">
                <a href="#" class="w-9 h-9 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-md hover:bg-white/30 transition">
                    <i class="ti ti-settings text-lg"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-md mx-auto px-5 profile-container">
        <!-- Profile Card -->
        <div class="bg-white rounded-3xl p-6 shadow-xl shadow-slate-200/50 flex items-center gap-5 mb-6">
            <div class="w-16 h-16 rounded-full overflow-hidden bg-slate-100 border-2 border-[#10b981]/20">
                <img src="{{ auth()->user()->profile_photo ? asset('storage/'.auth()->user()->profile_photo) : asset('assets/img/avatars/1.png') }}" class="w-full h-full object-cover">
            </div>
            <div class="flex-1">
                <h2 class="text-base font-black text-slate-800 leading-none mb-1">{{ auth()->user()->name }}</h2>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ auth()->user()->email }}</span>
            </div>
            <a href="{{ route('account.profile') }}" class="w-8 h-8 bg-slate-100 rounded-full flex items-center justify-center text-slate-400">
                <i class="ti ti-chevron-right text-sm"></i>
            </a>
        </div>

        <!-- Order Status Bar (Shopee Style) -->
        <div class="bg-white rounded-3xl p-5 shadow-sm mb-6">
            <div class="flex items-center justify-between mb-5 border-b pb-3">
                <h3 class="text-[12px] font-black text-slate-800 uppercase tracking-tight">Status Pesanan</h3>
                <a href="{{ route('account.orders') }}" class="text-[10px] font-bold text-[#006d5b] uppercase">Riwayat <i class="ti ti-chevron-right"></i></a>
            </div>
            <div class="flex justify-between px-1">
                <a href="{{ route('account.orders', ['status' => 'pending']) }}" class="flex flex-col items-center gap-2 relative">
                    <i class="ti ti-wallet text-2xl text-slate-400"></i>
                    <span class="text-[9px] font-bold text-slate-500 uppercase tracking-tighter">Belum Bayar</span>
                    @if(($counts['pending'] ?? 0) > 0) <div class="order-badge">{{ $counts['pending'] }}</div> @endif
                </a>
                <a href="{{ route('account.orders', ['status' => 'paid']) }}" class="flex flex-col items-center gap-2 relative">
                    <i class="ti ti-package text-2xl text-slate-400"></i>
                    <span class="text-[9px] font-bold text-slate-500 uppercase tracking-tighter">Dikemas</span>
                    @if(($counts['paid'] ?? 0) > 0) <div class="order-badge">{{ $counts['paid'] }}</div> @endif
                </a>
                <a href="{{ route('account.orders', ['status' => 'shipping']) }}" class="flex flex-col items-center gap-2 relative">
                    <i class="ti ti-truck text-2xl text-slate-400"></i>
                    <span class="text-[9px] font-bold text-slate-500 uppercase tracking-tighter">Dikirim</span>
                    @if(($counts['shipping'] ?? 0) > 0) <div class="order-badge">{{ $counts['shipping'] }}</div> @endif
                </a>
                <a href="{{ route('account.orders', ['status' => 'completed']) }}" class="flex flex-col items-center gap-2 relative">
                    <i class="ti ti-star text-2xl text-slate-400"></i>
                    <span class="text-[9px] font-bold text-slate-500 uppercase tracking-tighter">Beri Nilai</span>
                </a>
            </div>
        </div>

        <!-- Main Menus -->
        <div class="space-y-3">
            <a href="{{ route('account.profile') }}" class="menu-card shadow-sm">
                <div class="icon-wrapper bg-blue-50 text-blue-600">
                    <i class="ti ti-user-circle"></i>
                </div>
                <div class="flex-1">
                    <h4 class="font-bold text-slate-700 text-[13px]">Edit Profil</h4>
                    <p class="text-[9px] text-slate-400 uppercase tracking-widest">Atur nama & foto profil</p>
                </div>
                <i class="ti ti-chevron-right text-slate-300"></i>
            </a>

            <a href="{{ route('account.address') }}" class="menu-card shadow-sm">
                <div class="icon-wrapper bg-emerald-50 text-emerald-600">
                    <i class="ti ti-map-pin"></i>
                </div>
                <div class="flex-1">
                    <h4 class="font-bold text-slate-700 text-[13px]">Daftar Alamat</h4>
                    <p class="text-[9px] text-slate-400 uppercase tracking-widest">Atur tujuan pengiriman</p>
                </div>
                <i class="ti ti-chevron-right text-slate-300"></i>
            </a>

            <a href="https://wa.me/{{ env('CS_PHONE', '6281234567890') }}" class="menu-card shadow-sm">
                <div class="icon-wrapper bg-green-50 text-green-600">
                    <i class="ti ti-brand-whatsapp"></i>
                </div>
                <div class="flex-1">
                    <h4 class="font-bold text-slate-700 text-[13px]">Bantuan & Support</h4>
                    <p class="text-[9px] text-slate-400 uppercase tracking-widest">Hubungi CS via WhatsApp</p>
                </div>
                <i class="ti ti-chevron-right text-slate-300"></i>
            </a>

            <form method="POST" action="{{ route('logout') }}" class="pt-4">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-3 py-4 bg-red-50 text-red-600 rounded-3xl border border-red-100 font-black text-[12px] uppercase tracking-widest active:scale-95 transition">
                    <i class="ti ti-logout"></i>
                    KELUAR DARI AKUN
                </button>
            </form>
        </div>
    </div>

    @include('layouts.bottom-nav')

</body>
</html>
