<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Akun Saya | {{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700;900&display=swap" rel="stylesheet" />

    <!-- Icons (Tabler) -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/tabler-icons.css') }}" />

    <!-- Scripts / Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Public Sans', sans-serif;
            background-color: #f7f9fc;
        }

        .premium-header {
            background: linear-gradient(135deg, #006d5b 0%, #10b981 100%);
            height: 220px;
            border-bottom-left-radius: 50px;
            border-bottom-right-radius: 50px;
            position: relative;
            box-shadow: 0 10px 30px rgba(0, 109, 91, 0.2);
        }

        .abstract-shape {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .profile-container {
            margin-top: -80px;
            position: relative;
            z-index: 10;
        }

        .avatar-glow {
            box-shadow: 0 0 0 8px rgba(255, 255, 255, 0.5), 0 15px 35px rgba(0, 0, 0, 0.15);
        }

        .menu-card {
            background: white;
            border-radius: 24px;
            padding: 18px;
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 16px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(0, 0, 0, 0.03);
            text-decoration: none;
        }

        .menu-card:active {
            transform: scale(0.96);
            background-color: #f0fdf4;
        }

        .icon-wrapper {
            width: 48px;
            height: 48px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            flex-shrink: 0;
        }

        .badge-count {
            @apply bg-[#f53003] text-white text-[10px] px-1.5 py-0.5 rounded-full font-black;
        }
    </style>
</head>

<body class="antialiased pb-28">

    <!-- Premium Header -->
    <div class="premium-header overflow-hidden">
        <div class="abstract-shape w-40 h-40 -top-10 -left-10"></div>
        <div class="abstract-shape w-24 h-24 bottom-10 right-10 opacity-50"></div>
        
        <div class="flex items-center justify-between px-6 pt-8 text-white">
            <h1 class="text-xl font-black tracking-tight uppercase">Akun Saya</h1>
            <a href="#" class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-md transition active:scale-90">
                <i class="ti ti-bell text-lg"></i>
            </a>
        </div>
    </div>

    <!-- Profile Info -->
    <div class="max-w-md mx-auto px-6 profile-container">
        <div class="bg-white rounded-[32px] p-8 shadow-xl shadow-slate-200/50 flex flex-col items-center mb-8 border border-white">
            <div class="relative mb-6">
                <div class="w-28 h-28 rounded-full overflow-hidden avatar-glow bg-slate-100">
                    <img src="{{ auth()->user()->profile_photo ? asset('storage/'.auth()->user()->profile_photo) : asset('assets/img/avatars/1.png') }}" class="w-full h-full object-cover">
                </div>
                <button class="absolute bottom-1 right-1 w-8 h-8 bg-[#006d5b] text-white rounded-full border-2 border-white flex items-center justify-center shadow-lg transition active:scale-90 overflow-hidden">
                    <i class="ti ti-pencil ti-xs"></i>
                </button>
            </div>
            <h2 class="text-xl font-black text-slate-800 mb-1 tracking-tight">{{ auth()->user()->name }}</h2>
            <div class="flex items-center gap-2 px-3 py-1 bg-slate-100 rounded-full">
                <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span>
                <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ auth()->user()->email }}</span>
            </div>
        </div>

        <!-- Menu Section -->
        <div class="space-y-4">
            <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 ml-2 mb-4">Pengaturan Akun</h3>
            
            <a href="{{ route('account.profile') }}" class="menu-card shadow-sm hover:shadow-md">
                <div class="icon-wrapper bg-blue-50 text-blue-600">
                    <i class="ti ti-user-circle"></i>
                </div>
                <div class="flex-1">
                    <h4 class="font-bold text-slate-700 text-sm">Profil Lengkap</h4>
                    <p class="text-[10px] text-slate-400">Nama, Email, dan Password</p>
                </div>
                <i class="ti ti-chevron-right text-slate-300"></i>
            </a>

            <a href="{{ route('account.address') }}" class="menu-card shadow-sm hover:shadow-md">
                <div class="icon-wrapper bg-emerald-50 text-emerald-600">
                    <i class="ti ti-map-pin"></i>
                </div>
                <div class="flex-1">
                    <h4 class="font-bold text-slate-700 text-sm">Alamat Pengiriman</h4>
                    <p class="text-[10px] text-slate-400">Atur tujuan paket belanja</p>
                </div>
                <i class="ti ti-chevron-right text-slate-300"></i>
            </a>

            <a href="#" class="menu-card shadow-sm hover:shadow-md">
                <div class="icon-wrapper bg-orange-50 text-orange-600">
                    <i class="ti ti-receipt"></i>
                </div>
                <div class="flex-1">
                    <h4 class="font-bold text-slate-700 text-sm">Riwayat Pesanan</h4>
                    <p class="text-[10px] text-slate-400">Lihat status pesanan aktif</p>
                </div>
                <div class="badge-count">3</div>
                <i class="ti ti-chevron-right text-slate-300"></i>
            </a>

            <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 ml-2 mt-8 mb-4">Bantuan & Lainnya</h3>

            <a href="https://wa.me/{{ auth()->user()->phone ?? '6281234567890' }}" class="menu-card shadow-sm hover:shadow-md">
                <div class="icon-wrapper bg-green-50 text-green-600">
                    <i class="ti ti-brand-whatsapp"></i>
                </div>
                <div class="flex-1">
                    <h4 class="font-bold text-slate-700 text-sm">Hubungi Kami</h4>
                    <p class="text-[10px] text-slate-400">Bantuan CS fast respon</p>
                </div>
                <i class="ti ti-chevron-right text-slate-300"></i>
            </a>

            <!-- Logout -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full menu-card border-none bg-red-50 hover:bg-red-100 transition shadow-none active:scale-95 group">
                    <div class="icon-wrapper bg-red-500 text-white shadow-lg shadow-red-200">
                        <i class="ti ti-power"></i>
                    </div>
                    <div class="flex-1 text-left">
                        <h4 class="font-black text-red-600 text-sm uppercase tracking-widest">KELUAR AKUN</h4>
                    </div>
                </button>
            </form>
        </div>
    </div>

    <!-- Bottom Nav Wrapper -->
    @include('layouts.bottom-nav')

</body>
</html>
