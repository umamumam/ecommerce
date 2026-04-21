<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Alamat Pengiriman | {{ config('app.name', 'Laravel') }}</title>

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
        .header-bg {
            background:#ffffff;
            border-bottom: 1px solid #eee;
        }
    </style>
</head>

<body class="antialiased pb-20">

    <!-- Header -->
    <div class="header-bg sticky top-0 z-50 px-6 py-4 flex items-center gap-4">
        <a href="{{ route('account') }}" class="text-slate-800">
            <i class="ti ti-arrow-narrow-left text-2xl"></i>
        </a>
        <h1 class="text-lg font-black text-slate-800 tracking-tight">Alamat Pengiriman</h1>
    </div>

    <main class="max-w-md mx-auto px-6 py-8">

        <form action="{{ route('account.address.update') }}" method="POST">
            @csrf
            
            <div class="space-y-6">
                <!-- Current Address Summary Card -->
                <div class="bg-[#f0fdf4] border border-teal-50 rounded-3xl p-6 mb-8">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-8 h-8 bg-[#006d5b] text-white rounded-xl flex items-center justify-center">
                            <i class="ti ti-home text-lg"></i>
                        </div>
                        <h3 class="font-black text-slate-800 text-sm">Alamat Utama</h3>
                    </div>
                    <p class="text-xs font-bold text-[#006d5b] break-words leading-relaxed">
                        {{ $user->address ?? 'Belum ada alamat' }}, 
                        {{ $user->district ?? '-' }}, 
                        {{ $user->city ?? '-' }}, 
                        {{ $user->province ?? '-' }} 
                        [{{ $user->postal_code ?? '-' }}]
                    </p>
                </div>

                <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 ml-2 mb-4">Edit Lokasi Pengiriman</h3>

                <div>
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 block ml-1">Provinsi</label>
                    <input type="text" name="province" value="{{ old('province', $user->province) }}" class="w-full bg-white border-slate-100 rounded-2xl py-3 px-4 focus:ring-2 focus:ring-[#006d5b] focus:border-[#006d5b] text-sm font-medium shadow-sm transition" placeholder="Contoh: Jawa Tengah">
                </div>

                <div>
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 block ml-1">Kabupaten / Kota</label>
                    <input type="text" name="city" value="{{ old('city', $user->city) }}" class="w-full bg-white border-slate-100 rounded-2xl py-3 px-4 focus:ring-2 focus:ring-[#006d5b] focus:border-[#006d5b] text-sm font-medium shadow-sm transition" placeholder="Contoh: Pati">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 block ml-1">Kecamatan</label>
                        <input type="text" name="district" value="{{ old('district', $user->district) }}" class="w-full bg-white border-slate-100 rounded-2xl py-3 px-4 focus:ring-2 focus:ring-[#006d5b] focus:border-[#006d5b] text-sm font-medium shadow-sm transition" placeholder="Contoh: Cluwak">
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 block ml-1">Kode Pos</label>
                        <input type="text" name="postal_code" value="{{ old('postal_code', $user->postal_code) }}" class="w-full bg-white border-slate-100 rounded-2xl py-3 px-4 focus:ring-2 focus:ring-[#006d5b] focus:border-[#006d5b] text-sm font-medium shadow-sm transition" placeholder="59157">
                    </div>
                </div>

                <div>
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 block ml-1">Alamat Lengkap</label>
                    <textarea name="address" rows="3" class="w-full bg-white border-slate-100 rounded-2xl py-3 px-4 focus:ring-2 focus:ring-[#006d5b] focus:border-[#006d5b] text-sm font-medium shadow-sm transition" placeholder="Nama Jalan, Gedung, No. Rumah">{{ old('address', $user->address) }}</textarea>
                </div>
            </div>

            <div class="mt-12">
                <button type="submit" class="w-full bg-[#006d5b] text-white font-black py-4 rounded-2xl shadow-xl shadow-teal-100 transition active:scale-95">UDPATE ALAMAT</button>
            </div>
        </form>
    </main>

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
