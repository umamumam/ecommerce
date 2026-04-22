<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Selesaikan Pesanan | {{ config('app.name', 'Laravel') }}</title>

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

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .input-shopee {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #ddd;
            border-radius: 2px;
            font-size: 14px;
            transition: border-color 0.2s;
        }

        .input-shopee:focus {
            border-color: #006d5b;
            outline: none;
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

<body class="antialiased" x-data="checkoutPage()">

    <header class="bg-white sticky top-0 z-50 border-b border-slate-100 shadow-sm">
        <div class="max-w-[1400px] mx-auto px-4 py-4 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="/cart" class="flex items-center gap-2 text-slate-500 hover:text-[#006d5b] transition">
                    <i class="ti ti-arrow-left"></i>
                    <span class="text-sm font-medium">Checkout</span>
                </a>
            </div>

            <div class="hidden md:flex items-center gap-8">
                <div class="flex items-center gap-2 text-[11px] font-medium uppercase tracking-widest"
                    :class="!selectedArea ? 'text-[#006d5b]' : 'text-slate-400'">
                    <span
                        class="w-5 h-5 rounded-full border border-current flex items-center justify-center text-[10px]">1</span>
                    Alamat
                </div>
                <i class="ti ti-chevron-right text-slate-300"></i>
                <div class="flex items-center gap-2 text-[11px] font-medium uppercase tracking-widest"
                    :class="selectedArea && !selectedRate ? 'text-[#006d5b]' : 'text-slate-400'">
                    <span
                        class="w-5 h-5 rounded-full border border-current flex items-center justify-center text-[10px]">2</span>
                    Pengiriman
                </div>
                <i class="ti ti-chevron-right text-slate-300"></i>
                <div class="flex items-center gap-2 text-[11px] font-medium uppercase tracking-widest"
                    :class="selectedRate ? 'text-[#006d5b]' : 'text-slate-400'">
                    <span
                        class="w-5 h-5 rounded-full border border-current flex items-center justify-center text-[10px]">3</span>
                    Pembayaran
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-[1400px] mx-auto md:px-4 py-4 md:py-6 lg:py-8 font-sans">
        <div class="flex flex-col lg:flex-row gap-4">
            <!-- Left: Sections -->
            <div class="w-full lg:flex-1 flex flex-col gap-4">

                <!-- Alamat Section -->
                <section class="bg-white p-4 md:p-6 shadow-sm border border-slate-100 mobile-flat">
                    <div class="flex items-center gap-3 mb-6 border-b pb-3">
                        <i class="ti ti-map-pin text-[#006d5b] text-xl"></i>
                        <h2 class="text-base font-medium text-slate-800">Alamat Pengiriman</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="text-[11px] font-medium text-slate-400 uppercase mb-2 block">Nama
                                Penerima</label>
                            <input type="text" x-model="recipientName" placeholder="Masukkan nama..."
                                class="input-shopee">
                        </div>
                        <div>
                            <label class="text-[11px] font-medium text-slate-400 uppercase mb-2 block">No.
                                WhatsApp</label>
                            <input type="text" x-model="recipientPhone" placeholder="Contoh: 0812..."
                                class="input-shopee">
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="text-[11px] font-medium text-slate-400 uppercase mb-2 block">Cari Kecamatan /
                            Kota</label>
                        <div class="relative">
                            <input type="text" x-model="areaSearch" @input.debounce.500ms="searchArea()"
                                placeholder="Ketik minimal 3 huruf..." class="input-shopee">
                            <i class="ti ti-search absolute right-3 top-1/2 -translate-y-1/2 text-slate-400"></i>

                            <!-- Search Results -->
                            <div x-show="areaResults.length > 0"
                                class="absolute left-0 right-0 mt-1 bg-white shadow-xl border border-slate-100 z-50 max-h-60 overflow-y-auto no-scrollbar py-1"
                                x-cloak @click.away="areaResults = []">
                                <template x-for="area in areaResults" :key="area.id">
                                    <div @click="selectArea(area)"
                                        class="px-4 py-3 hover:bg-slate-50 cursor-pointer border-b border-slate-50 last:border-0 flex flex-col group transition">
                                        <span class="text-[13px] font-medium text-slate-800 group-hover:text-[#006d5b]"
                                            x-text="area.name"></span>
                                        <span class="text-[10px] text-slate-400 mt-1"
                                            x-text="area.formatted_address"></span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <div x-show="selectedArea" x-cloak
                        class="mb-6 bg-slate-50 p-4 rounded-sm border border-slate-200 flex items-center justify-between animate-fade-in">
                        <div class="flex flex-col">
                            <span class="text-[13px] font-medium text-[#006d5b]" x-text="selectedArea?.name"></span>
                            <span class="text-[11px] text-slate-400 mt-1"
                                x-text="'Kodepos: ' + selectedArea?.postal_code"></span>
                        </div>
                        <button @click="selectedArea = null; areaSearch = ''; courierRates = []"
                            class="text-slate-400 hover:text-red-500">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>

                    <div>
                        <label class="text-[11px] font-medium text-slate-400 uppercase mb-2 block">Detail Alamat (Nama
                            Jalan, No. Rumah)</label>
                        <textarea x-model="addressDetail" placeholder="Tuliskan alamat lengkap..."
                            class="input-shopee min-h-[100px]"></textarea>
                    </div>
                </section>

                <!-- Shipping Method Section -->
                <section class="bg-white p-4 md:p-6 shadow-sm border border-slate-100 mobile-flat" x-show="selectedArea"
                    x-cloak x-transition>
                    <div class="flex items-center gap-3 mb-6 border-b pb-3">
                        <i class="ti ti-truck text-[#006d5b] text-xl"></i>
                        <h2 class="text-base font-medium text-slate-800">Opsi Pengiriman</h2>
                    </div>

                    <div x-show="loadingRates" class="flex flex-col items-center py-10">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-[#006d5b] mb-4"></div>
                        <span class="text-[11px] text-slate-400 font-medium">Mencari kurir terbaik...</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3"
                        x-show="!loadingRates && courierRates.length > 0">
                        <template x-for="rate in courierRates" :key="rate.courier_code + rate.courier_service_code">
                            <div @click="selectCourier(rate)"
                                class="p-4 border rounded-sm cursor-pointer transition-all duration-200"
                                :class="selectedRate?.courier_service_code === rate.courier_service_code ? 'border-[#006d5b] bg-[#006d5b]/5' : 'border-slate-200 hover:border-[#006d5b]/50'">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-2">
                                        <img :src="rate.courier_logo" class="w-6 h-6 object-contain">
                                        <span class="text-[13px] font-medium uppercase text-slate-700"
                                            x-text="rate.courier_name"></span>
                                    </div>
                                    <span class="text-[13px] font-medium text-[#006d5b]"
                                        x-text="formatPrice(rate.price)"></span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-[11px] text-slate-500 font-medium uppercase"
                                        x-text="rate.courier_service_name"></span>
                                    <span class="text-[10px] text-slate-400"
                                        x-text="'Estimasi: ' + rate.duration"></span>
                                </div>
                            </div>
                        </template>
                    </div>
                </section>
            </div>

            <!-- Right: Order Summary -->
            <div class="w-full lg:w-[400px]">
                <div class="sticky top-24 flex flex-col gap-4">
                    <section class="bg-white p-4 md:p-6 shadow-sm border border-slate-100 mobile-flat">
                        <h2 class="text-sm font-medium text-slate-800 mb-6 border-b pb-3">Pesanan Anda</h2>

                        <!-- Cart Items -->
                        <div class="flex flex-col gap-4 mb-8 max-h-[400px] overflow-y-auto no-scrollbar">
                            @foreach($cart as $id => $item)
                            <div class="flex gap-3 items-start">
                                <div class="w-16 h-16 rounded-sm bg-slate-50 border shrink-0">
                                    @php
                                    $img = $item['image'] ? (Str::startsWith($item['image'], 'http') ? $item['image'] :
                                    asset('storage/'.$item['image'])) : asset('assets/img/elements/1.jpg');
                                    @endphp
                                    <img src="{{ $img }}" class="w-full h-full object-cover rounded-sm">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-[12px] font-normal text-slate-800 line-clamp-2 leading-snug mb-1">{{
                                        $item['name'] }}</h3>
                                    <div class="flex justify-between items-center">
                                        <span class="text-[11px] text-slate-400">x{{ $item['quantity'] }}</span>
                                        <span class="text-[13px] font-medium text-slate-800">Rp{{
                                            number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="space-y-4 mb-8">
                            <div class="flex justify-between text-sm">
                                <span class="text-slate-500">Subtotal Produk</span>
                                <span class="font-medium text-slate-800">Rp{{ number_format($totalPrice, 0, ',', '.')
                                    }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-slate-500">Subtotal Pengiriman</span>
                                <span class="font-medium text-[#006d5b]"
                                    x-text="selectedRate ? formatPrice(selectedRate.price) : 'Rp 0'"></span>
                            </div>
                            <div class="pt-4 border-t flex justify-between items-center">
                                <span class="text-base font-medium">Total Pembayaran</span>
                                <span class="text-xl font-medium price-teal"
                                    x-text="formatPrice({{ $totalPrice }} + (selectedRate ? selectedRate.price : 0))"></span>
                            </div>
                        </div>

                        <form action="{{ route('checkout.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="shipping_name" :value="recipientName">
                            <input type="hidden" name="shipping_phone" :value="recipientPhone">
                            <input type="hidden" name="shipping_area_id" :value="selectedArea?.id">
                            <input type="hidden" name="shipping_address" :value="addressDetail">
                            <input type="hidden" name="shipping_courier" :value="selectedRate?.courier_code">
                            <input type="hidden" name="shipping_service" :value="selectedRate?.courier_service_code">
                            <input type="hidden" name="shipping_price" :value="selectedRate?.price">
                            <input type="hidden" name="shipping_postal_code" :value="selectedArea?.postcode">

                            <button type="submit"
                                :disabled="!selectedRate || !addressDetail || !recipientName || !recipientPhone || loadingRates"
                                class="w-full py-4 btn-primary-teal rounded-sm text-sm font-medium tracking-widest disabled:bg-slate-300 disabled:cursor-not-allowed">
                                BUAT PESANAN
                            </button>
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </main>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function checkoutPage() {
            return {
                recipientName: '{{ auth()->check() ? auth()->user()->name : "" }}',
                recipientPhone: '{{ auth()->check() ? (auth()->user()->phone ?? "") : "" }}',
                areaSearch: '{{ $initialPostal ?: ($initialLocationName ?: "") }}',
                areaResults: [],
                selectedArea: null,
                addressDetail: '{{ $initialAddress ?: "" }}',
                loadingRates: false,
                courierRates: [],
                selectedRate: null,

                init() {
                    if (this.areaSearch.length >= 3) {
                        this.searchArea();
                    }
                },

                async searchArea() {
                    if (this.areaSearch.length < 3) return;
                    try {
                        const res = await fetch(`/shipping/areas?q=${this.areaSearch}`);
                        const data = await res.json();
                        this.areaResults = data.areas || [];
                    } catch (e) {
                        console.error(e);
                    }
                },

                selectArea(area) {
                    this.selectedArea = area;
                    this.areaResults = [];
                    this.areaSearch = area.name + (area.postcode ? ' (' + area.postcode + ')' : '');
                    this.fetchRates();
                },

                async fetchRates() {
                    if (!this.selectedArea) return;
                    this.loadingRates = true;
                    this.selectedRate = null;
                    
                    const items = [
                        @foreach($cart as $item)
                        {
                            name: '{{ $item['name'] }}',
                            description: 'E-commerce Item',
                            value: {{ $item['price'] }},
                            weight: {{ $item['weight'] }},
                            quantity: {{ $item['quantity'] }}
                        },
                        @endforeach
                    ];

                    try {
                        const res = await fetch('/shipping/rates', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                destination_area_id: this.selectedArea.id,
                                items: items
                            })
                        });
                        const data = await res.json();
                        this.courierRates = (data.pricing || []).filter(r => r.price > 0);
                    } catch (e) {
                        console.error(e);
                    } finally {
                        this.loadingRates = false;
                    }
                },

                selectCourier(rate) {
                    this.selectedRate = rate;
                    window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
                },

                formatPrice(price) {
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0
                    }).format(price);
                }
            }
        }
    </script>

    @include('layouts.bottom-nav')
</body>

</html>