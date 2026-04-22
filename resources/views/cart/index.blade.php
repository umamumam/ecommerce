<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Keranjang Belanja | {{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700;900&display=swap"
        rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/tabler-icons.css') }}" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Public Sans', sans-serif;
            background-color: #f6f8fa;
            color: #1e293b;
        }

        .btn-primary-teal {
            background: #006d5b;
            color: white !important;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-primary-teal:hover {
            background: #004d40;
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 109, 91, 0.2);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        .cart-item-row {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .cart-item-row:hover {
            transform: scale(1.01);
            background: rgba(255, 255, 255, 1);
            box-shadow: 0 15px 40px -12px rgba(0, 0, 0, 0.08);
        }

        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="antialiased pb-24 lg:pb-10">

    <header class="bg-white/90 backdrop-blur-md sticky top-0 z-50 border-b border-slate-100">
        <div class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between">
            <a href="/" class="text-2xl font-black flex items-center gap-1 group">
                <span class="text-[#006d5b] group-hover:tracking-wider transition-all duration-500">TOKO</span><span class="text-[#f53003]">KITA</span>
            </a>
            <a href="/" class="flex items-center gap-2 text-slate-500 hover:text-[#006d5b] transition font-bold text-xs uppercase tracking-widest">
                <i class="ti ti-arrow-left"></i>
                <span>Lanjut Belanja</span>
            </a>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-4 py-10 lg:py-16">
        <div class="flex flex-col lg:flex-row gap-10 items-start">
            
            <!-- Left: Cart Items -->
            <div class="w-full lg:w-[65%] flex flex-col gap-6">
                
                <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-slate-200/50 overflow-hidden border border-slate-100">
                    <div class="p-8 lg:p-10 flex items-center justify-between border-b border-slate-50 bg-slate-50/30">
                        <h2 class="text-sm font-black uppercase tracking-[0.2em] text-slate-800">List Produk ({{ count($cart) }})</h2>
                        <button onclick="location.reload()" class="text-emerald-500 hover:text-emerald-600 font-black text-[10px] uppercase tracking-widest flex items-center gap-2">
                             <i class="ti ti-refresh text-lg"></i> Refresh
                        </button>
                    </div>

                    <div class="p-6 lg:p-8">
                        @if(count($cart) > 0)
                            <div class="hidden md:grid grid-cols-12 gap-4 pb-6 px-4 border-b border-slate-50 text-[9px] font-black uppercase tracking-widest text-slate-400">
                                <div class="col-span-5">Produk</div>
                                <div class="col-span-2 text-center">Harga</div>
                                <div class="col-span-2 text-center">Qty</div>
                                <div class="col-span-2 text-center">Subtotal</div>
                                <div class="col-span-1"></div>
                            </div>

                            <div class="divide-y divide-slate-50">
                                @php $total = 0 @endphp
                                @foreach($cart as $id => $details)
                                @php $total += $details['price'] * $details['quantity'] @endphp
                                <div class="cart-item-row grid grid-cols-1 md:grid-cols-12 gap-6 items-center py-8 px-4">
                                    
                                    <!-- Product Info -->
                                    <div class="col-span-1 md:col-span-5 flex items-center gap-5">
                                        <div class="w-20 h-20 rounded-2xl overflow-hidden bg-slate-100 shrink-0 border border-slate-200 shadow-sm">
                                            @php
                                                $imgSrc = $details['image'] ? (Str::startsWith($details['image'], 'http') ? $details['image'] : asset('storage/'.$details['image'])) : asset('assets/img/elements/1.jpg');
                                            @endphp
                                            <img src="{{ $imgSrc }}" class="w-full h-full object-cover">
                                        </div>
                                        <div class="flex flex-col min-w-0">
                                            <h3 class="text-xs font-black text-slate-800 uppercase tracking-tight line-clamp-2 md:line-clamp-none mb-1.5">{{ $details['name'] }}</h3>
                                            <div class="flex flex-wrap gap-2">
                                                <span class="text-[8px] font-black uppercase tracking-widest text-[#006d5b] bg-[#006d5b]/10 px-2 py-0.5 rounded">{{ $details['variant_1'] ?? 'Default' }}</span>
                                                @if($details['variant_2'])
                                                    <span class="text-[8px] font-black uppercase tracking-widest text-slate-400 bg-slate-100 px-2 py-0.5 rounded">{{ $details['variant_2'] }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Price -->
                                    <div class="col-span-1 md:col-span-2 text-left md:text-center">
                                        <span class="md:hidden text-[8px] font-black uppercase text-slate-400 tracking-widest block mb-1">Harga</span>
                                        <span class="text-xs font-black text-slate-600 italic">Rp {{ number_format($details['price'], 0, ',', '.') }}</span>
                                    </div>

                                    <!-- Qty -->
                                    <div class="col-span-1 md:col-span-2 flex flex-col md:items-center gap-2">
                                        <span class="md:hidden text-[8px] font-black uppercase text-slate-400 tracking-widest block mb-1">Kuantitas</span>
                                        <div class="flex items-center bg-slate-100 p-1 rounded-xl w-fit border border-slate-200">
                                            <button class="w-7 h-7 rounded-lg hover:bg-white hover:text-emerald-600 transition shadow-sm flex items-center justify-center update-cart" data-id="{{ $id }}" data-qty="{{ $details['quantity'] - 1 }}">
                                                <i class="ti ti-minus text-[10px]"></i>
                                            </button>
                                            <span class="w-8 text-center font-black text-xs">{{ $details['quantity'] }}</span>
                                            <button class="w-7 h-7 rounded-lg hover:bg-white hover:text-emerald-600 transition shadow-sm flex items-center justify-center update-cart" data-id="{{ $id }}" data-qty="{{ $details['quantity'] + 1 }}">
                                                <i class="ti ti-plus text-[10px]"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Subtotal -->
                                    <div class="col-span-1 md:col-span-2 text-left md:text-center">
                                        <span class="md:hidden text-[8px] font-black uppercase text-slate-400 tracking-widest block mb-1">Subtotal</span>
                                        <span class="text-sm font-black text-[#006d5b] italic">Rp {{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}</span>
                                    </div>

                                    <!-- Trash -->
                                    <div class="col-span-1 md:col-span-1 flex justify-end md:justify-center">
                                        <button class="w-10 h-10 rounded-2xl bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-all flex items-center justify-center remove-from-cart border border-red-100" data-id="{{ $id }}">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="py-20 flex flex-col items-center text-center">
                                <div class="w-24 h-24 bg-slate-100 text-slate-300 rounded-[2.5rem] flex items-center justify-center mb-6">
                                    <i class="ti ti-shopping-cart-off text-5xl"></i>
                                </div>
                                <h3 class="text-lg font-black text-slate-800 uppercase tracking-tight mb-2">Keranjang Kosong</h3>
                                <p class="text-xs text-slate-400 font-bold max-w-xs mb-8">Wah, keranjang belanja kamu masih kosong nih. Yuk cari produk impianmu!</p>
                                <a href="/" class="btn-primary-teal px-10 py-4 rounded-3xl font-black text-xs uppercase tracking-widest">Mulai Belanja</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right: Summary -->
            @if(count($cart) > 0)
            <div class="w-full lg:w-[35%]">
                <div class="bg-white rounded-[2.5rem] p-8 lg:p-10 shadow-2xl shadow-slate-200/50 border border-slate-100 sticky top-28">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-12 h-12 bg-emerald-50 text-[#006d5b] rounded-2xl flex items-center justify-center border border-emerald-100 shadow-inner">
                            <i class="ti ti-receipt text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-black uppercase tracking-widest text-slate-800 mb-0.5 leading-none">Ringkasan</h3>
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Detail Pesanan Anda</p>
                        </div>
                    </div>

                    <div class="space-y-5 mb-10">
                        <div class="flex justify-between items-center">
                            <span class="text-[10px] font-black uppercase tracking-widest text-slate-500">Total Harga</span>
                            <span class="text-xs font-black text-slate-800 italic">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-[10px] font-black uppercase tracking-widest text-slate-500">Diskon</span>
                            <span class="text-xs font-black text-emerald-500 italic">- Rp 0</span>
                        </div>
                        <div class="pt-6 border-t border-slate-100 flex justify-between items-center">
                            <span class="text-[11px] font-black uppercase tracking-widest text-slate-800">Total Bayar</span>
                            <span class="text-2xl font-black text-[#006d5b] tracking-tighter italic">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <a href="{{ route('checkout.index') }}" class="w-full bg-[#006d5b] hover:bg-[#004d40] text-white py-5 rounded-[2rem] font-black text-xs uppercase tracking-[0.2em] shadow-xl shadow-emerald-900/20 flex items-center justify-center gap-3 transition-all transform active:scale-95 group">
                        PROSES CHECKOUT <i class="ti ti-arrow-right group-hover:translate-x-2 transition"></i>
                    </a>

                    <div class="mt-8 flex flex-col items-center gap-4 py-4 px-6 bg-slate-50/50 rounded-2xl border border-slate-100">
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-[0.2em] flex items-center gap-2">
                             <i class="ti ti-shield-check text-emerald-500 text-base"></i> Transaksi Aman & Terjamin
                        </p>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </main>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- JQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            $(".update-cart").click(function (e) {
                e.preventDefault();
                var ele = $(this);
                var qty = ele.attr("data-qty");
                if(qty < 1) return;

                $.ajax({
                    url: '{{ route('cart.update') }}',
                    method: "post",
                    data: {
                        _token: '{{ csrf_token() }}', 
                        id: ele.attr("data-id"), 
                        quantity: qty,
                        _method: 'PATCH'
                    },
                    success: function (response) {
                        window.location.reload();
                    }
                });
            });

            $(".remove-from-cart").click(function (e) {
                e.preventDefault();
                var ele = $(this);

                Swal.fire({
                    title: 'Hapus Item?',
                    text: 'Produk akan dihapus dari keranjang.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#006d5b',
                    cancelButtonColor: '#f53003',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route('cart.remove') }}',
                            method: "POST",
                            data: {
                                _token: '{{ csrf_token() }}', 
                                id: ele.attr("data-id")
                            },
                            success: function (response) {
                                window.location.reload();
                            }
                        });
                    }
                })
            });
        });
    </script>
    
    @include('layouts.bottom-nav')
</body>

</html>
