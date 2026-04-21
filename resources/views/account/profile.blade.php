<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Profil | {{ config('app.name', 'Laravel') }}</title>

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
        <h1 class="text-lg font-black text-slate-800 tracking-tight">Edit Profil</h1>
    </div>

    <main class="max-w-md mx-auto px-6 py-8">

        <form action="{{ route('account.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <!-- Profile Photo Section -->
            <div class="flex flex-col items-center mb-10">
                <div class="relative group">
                    <div class="w-32 h-32 rounded-full border-4 border-white shadow-2xl overflow-hidden bg-slate-100">
                        <img id="avatar-preview" src="{{ $user->profile_photo ? asset('storage/'.$user->profile_photo) : asset('assets/img/avatars/1.png') }}" class="w-full h-full object-cover">
                    </div>
                    <label for="profile_photo" class="absolute bottom-1 right-1 w-10 h-10 bg-[#006d5b] text-white rounded-full border-4 border-white flex items-center justify-center shadow-lg transition active:scale-90 cursor-pointer">
                        <i class="ti ti-camera"></i>
                        <input type="file" id="profile_photo" name="profile_photo" class="hidden" accept="image/*" onchange="previewImage(this)">
                    </label>
                </div>
                <p class="text-[10px] font-black text-slate-400 mt-4 tracking-widest uppercase">Ganti Foto Profil</p>
            </div>

            <!-- User Info Section -->
            <div class="space-y-6">
                <div>
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 block ml-1">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full bg-white border-slate-100 rounded-2xl py-3 px-4 focus:ring-2 focus:ring-[#006d5b] focus:border-[#006d5b] text-sm font-medium shadow-sm transition" placeholder="Masukkan nama lengkap">
                    @error('name') <span class="text-[10px] text-red-500 font-bold mt-1 ml-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 block ml-1">Username</label>
                    <input type="text" name="username" value="{{ old('username', $user->username) }}" class="w-full bg-white border-slate-100 rounded-2xl py-3 px-4 focus:ring-2 focus:ring-[#006d5b] focus:border-[#006d5b] text-sm font-medium shadow-sm transition" placeholder="Username unik">
                    @error('username') <span class="text-[10px] text-red-500 font-bold mt-1 ml-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 block ml-1">Email (Akun)</label>
                    <input type="email" value="{{ $user->email }}" class="w-full bg-slate-50 border-slate-100 rounded-2xl py-3 px-4 text-slate-400 text-sm font-medium cursor-not-allowed" disabled>
                    <p class="text-[9px] text-slate-400 mt-1 ml-1 italic">* Email tidak dapat diubah sendiri untuk keamanan.</p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 block ml-1">Jenis Kelamin</label>
                        <select name="gender" class="w-full bg-white border-slate-100 rounded-2xl py-3 px-4 focus:ring-2 focus:ring-[#006d5b] focus:border-[#006d5b] text-sm font-medium shadow-sm transition">
                            <option value="">Pilih</option>
                            <option value="Laki-laki" {{ $user->gender == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ $user->gender == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 block ml-1">Tgl Lahir</label>
                        <input type="date" name="dob" value="{{ old('dob', $user->dob) }}" class="w-full bg-white border-slate-100 rounded-2xl py-3 px-4 focus:ring-2 focus:ring-[#006d5b] focus:border-[#006d5b] text-sm font-medium shadow-sm transition">
                    </div>
                </div>

                <div>
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 block ml-1">Nomor WhatsApp</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full bg-white border-slate-100 rounded-2xl py-3 px-4 focus:ring-2 focus:ring-[#006d5b] focus:border-[#006d5b] text-sm font-medium shadow-sm transition" placeholder="0812...">
                    @error('phone') <span class="text-[10px] text-red-500 font-bold mt-1 ml-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="mt-12">
                <button type="submit" class="w-full bg-[#006d5b] text-white font-black py-4 rounded-2xl shadow-xl shadow-teal-100 transition active:scale-95">SIMPAN PERUBAHAN</button>
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

        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('avatar-preview').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>
</html>
