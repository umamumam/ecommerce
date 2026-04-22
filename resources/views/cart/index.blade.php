<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Keranjang Belanja | {{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/tabler-icons.css') }}" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Public Sans', sans-serif;
            background-color: #f5f5f5;
            color: #222;
        }

        .btn-primary-teal {
            background-color: #006d5b;
            color: white !important;
            transition: opacity 0.2s;
        }

        .btn-primary-teal:hover {
            opacity: 0.9;
        }

        .price-teal {
            color: #006d5b;
        }

        [x-cloak] {
            display: none !important;
        }

        .line-clamp-1 {
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        @media (max-width: 640px) {
            .mobile-flat {
                border-radius: 0 !important;
                box-shadow: none !important;
                border: none !important;
            }
        }
    </style>
</head>

<body class="antialiased pb-24 lg:pb-10">

    <header class="bg-white sticky top-0 z-50 border-b border-slate-100 shadow-sm">
        <div class="max-w-[1400px] mx-auto px-4 py-3 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="/" class="text-2xl font-black flex items-center gap-1">
                    <span class="text-[#006d5b]">TOKO</span><span class="text-[#f53d2d]">KITA</span>
                </a>
                <div class="h-6 w-[1px] bg-slate-200 hidden md:block"></div>
                <h1 class="text-lg font-medium text-slate-700 hidden md:block">Keranjang Belanja</h1>
            </div>
            <a href="/" class="text-xs font-medium text-slate-500 hover:text-[#006d5b]">Lanjut Belanja</a>
        </div>
    </header>

    <main class="max-w-[1400px] mx-auto md:px-4 py-4 md:py-6 lg:py-8 font-sans">
        <div class="flex flex-col lg:flex-row gap-4 items-start">

            <!-- Left: Cart Items -->
            <div class="w-full lg:flex-1">

                <!-- Desktop Header -->
                <div
                    class="hidden md:grid grid-cols-12 gap-4 p-4 bg-white mb-3 rounded-sm shadow-sm text-[13px] font-medium text-slate-500 border border-slate-100">
                    <div class="col-span-6 flex items-center gap-4">
                        <span>Produk</span>
                    </div>
                    <div class="col-span-2 text-center">Harga Satuan</div>
                    <div class="col-span-2 text-center">Kuantitas</div>
                    <div class="col-span-1 text-center">Total Harga</div>
                    <div class="col-span-1 text-center">Aksi</div>
                </div>

                <div class="bg-white shadow-sm border border-slate-100 mobile-flat">
                    @if(count($cart) > 0)
                    <div class="divide-y divide-slate-100">
                        @php $total = 0 @endphp
                        @foreach($cart as $id => $details)
                        @php $total += $details['price'] * $details['quantity'] @endphp
                        <div class="p-4 md:p-6 grid grid-cols-1 md:grid-cols-12 gap-4 items-center">

                            <!-- Product Info -->
                            <div class="col-span-1 md:col-span-6 flex items-start gap-3 md:gap-4">
                                <div
                                    class="w-20 h-20 md:w-24 md:h-24 rounded-sm overflow-hidden bg-slate-100 shrink-0 border border-slate-100">
                                    @php
                                    $imgSrc = $details['image'] ? (Str::startsWith($details['image'], 'http') ?
                                    $details['image'] : asset('storage/'.$details['image'])) :
                                    asset('assets/img/elements/1.jpg');
                                    @endphp
                                    <img src="{{ $imgSrc }}" class="w-full h-full object-cover">
                                </div>
                                <div class="flex flex-col min-w-0 flex-1">
                                    <h3
                                        class="text-[13px] md:text-sm font-normal text-slate-800 line-clamp-2 leading-snug mb-1">
                                        {{ $details['name'] }}</h3>
                                    <div class="flex flex-wrap gap-2 mb-2">
                                        @if($details['variant_1'])
                                        <span class="text-[11px] text-slate-400">Variasi: {{ $details['variant_1']
                                            }}</span>
                                        @endif
                                        @if($details['variant_2'])
                                        <span class="text-[11px] text-slate-400">, {{ $details['variant_2'] }}</span>
                                        @endif
                                    </div>
                                    <div class="md:hidden flex items-center justify-between">
                                        <span class="price-teal text-[14px] font-medium">Rp{{ number_format($details['price'], 0,
                                            ',', '.') }}</span>
                                        <div class="flex items-center scale-90 origin-right">
                                            <button
                                                class="w-8 h-8 border border-slate-200 flex items-center justify-center update-cart"
                                                data-id="{{ $id }}" data-qty="{{ $details['quantity'] - 1 }}">-</button>
                                            <span
                                                class="w-10 h-8 border-t border-b border-slate-200 flex items-center justify-center text-xs">{{
                                                $details['quantity'] }}</span>
                                            <button
                                                class="w-8 h-8 border border-slate-200 flex items-center justify-center update-cart"
                                                data-id="{{ $id }}" data-qty="{{ $details['quantity'] + 1 }}">+</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Price (Desktop) -->
                            <div class="hidden md:block col-span-2 text-center text-sm font-normal text-slate-800">
                                Rp{{ number_format($details['price'], 0, ',', '.') }}
                            </div>

                            <!-- Qty (Desktop) -->
                            <div class="hidden md:flex col-span-2 justify-center">
                                <div class="flex items-center">
                                    <button
                                        class="w-8 h-8 border border-slate-200 hover:bg-slate-50 transition update-cart"
                                        data-id="{{ $id }}" data-qty="{{ $details['quantity'] - 1 }}">-</button>
                                    <input type="text" value="{{ $details['quantity'] }}" readonly
                                        class="w-12 h-8 border-t border-b border-slate-200 text-center text-sm focus:outline-none">
                                    <button
                                        class="w-8 h-8 border border-slate-200 hover:bg-slate-50 transition update-cart"
                                        data-id="{{ $id }}" data-qty="{{ $details['quantity'] + 1 }}">+</button>
                                </div>
                            </div>

                            <!-- Total (Desktop) -->
                            <div class="hidden md:block col-span-1 text-center text-sm font-medium price-teal">
                                Rp{{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}
                            </div>

                            <!-- Actions -->
                            <div class="col-span-1 flex justify-end md:justify-center">
                                <button
                                    class="text-xs md:text-sm text-slate-500 hover:text-[#006d5b] transition remove-from-cart"
                                    data-id="{{ $id }}">Hapus</button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="py-20 flex flex-col items-center text-center">
                        <i class="ti ti-shopping-cart-off text-6xl text-slate-200 mb-4"></i>
                        <h3 class="text-base font-medium text-slate-600 mb-6">Keranjang kosong</h3>
                        <a href="/" class="btn-primary-teal px-10 py-2 rounded-sm text-sm">Belanja Sekarang</a>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Right: Summary -->
            @if(count($cart) > 0)
            <div class="w-full lg:w-[350px]">
                <div class="bg-white p-4 md:p-6 shadow-sm border border-slate-100 mobile-flat sticky top-24">
                    <h3 class="text-sm font-medium text-slate-800 mb-6 border-b pb-3">Ringkasan Pesanan</h3>

                    <div class="space-y-4 mb-8">
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500">Subtotal</span>
                            <span>Rp{{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500">Voucher</span>
                            <span class="text-slate-400 cursor-pointer hover:text-[#006d5b]">Pilih Voucher</span>
                        </div>
                        <div class="pt-4 border-t flex justify-between">
                            <span class="text-base font-medium">Total</span>
                            <span class="text-lg font-medium price-teal">Rp{{ number_format($total, 0, ',', '.')
                                }}</span>
                        </div>
                    </div>

                    <a href="{{ route('checkout.index') }}"
                        class="block w-full btn-primary-teal py-3 text-center rounded-sm text-sm font-medium">Checkout
                        Sekarang</a>
                </div>
            </div>
            @endif

        </div>
    </main>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                    title: 'Hapus produk?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#006d5b',
                    cancelButtonColor: '#999',
                    confirmButtonText: 'Ya, Hapus',
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