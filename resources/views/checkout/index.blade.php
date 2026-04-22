<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Selesaikan Pesanan | {{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700;900&display=swap"
        rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/tabler-icons.css') }}" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Public Sans', sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        [x-cloak] {
            display: none !important;
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .step-active {
            color: #006d5b;
            border-bottom: 3px solid #006d5b;
        }
    </style>
</head>

<body class="antialiased" x-data="checkoutPage()">

    <header class="bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-slate-100">
        <div class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between">
            <a href="/cart" class="flex items-center gap-2 text-slate-500 hover:text-[#006d5b] transition font-bold text-xs uppercase tracking-widest">
                <i class="ti ti-arrow-left"></i>
                <span>Kembali ke Keranjang</span>
            </a>
            <div class="hidden md:flex items-center gap-8">
                <div class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest" :class="!selectedArea ? 'text-[#006d5b]' : 'text-slate-400'">
                    <span class="w-5 h-5 rounded-full border border-current flex items-center justify-center">1</span>
                    Alamat
                </div>
                <i class="ti ti-chevron-right text-slate-300"></i>
                <div class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest" :class="selectedArea && !selectedRate ? 'text-[#006d5b]' : 'text-slate-400'">
                    <span class="w-5 h-5 rounded-full border border-current flex items-center justify-center">2</span>
                    Pengiriman
                </div>
                <i class="ti ti-chevron-right text-slate-300"></i>
                <div class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest" :class="selectedRate ? 'text-[#006d5b]' : 'text-slate-400'">
                    <span class="w-5 h-5 rounded-full border border-current flex items-center justify-center">3</span>
                    Pembayaran
                </div>
            </div>
            <div class="md:hidden text-xs font-black uppercase text-[#006d5b]">Checkout</div>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-4 py-8 lg:py-12">
        <div class="flex flex-col lg:flex-row gap-10">
            <!-- Left: Form -->
            <div class="w-full lg:w-2/3 flex flex-col gap-8">

                <!-- Recipient Info -->
                <section class="bg-white p-6 lg:p-10 shadow-2xl shadow-slate-200/50 rounded-[2.5rem] border border-slate-50">
                    <div class="flex items-center gap-4 mb-8">
                        <div
                            class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center shadow-inner">
                            <i class="ti ti-user text-2xl"></i>
                        </div>
                        <h2 class="text-xl font-black uppercase tracking-tight text-slate-900">Informasi Penerima</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest mb-3 block">Nama
                                Lengkap</label>
                            <input type="text" x-model="recipientName" placeholder="Nama Penerima"
                                class="w-full px-6 py-4 rounded-2xl border border-slate-100 bg-slate-50 focus:bg-white focus:border-[#006d5b] focus:ring-0 transition font-bold text-sm">
                        </div>
                        <div>
                            <label
                                class="text-[10px] font-black uppercase text-slate-400 tracking-widest mb-3 block">Nomor
                                WhatsApp</label>
                            <input type="text" x-model="recipientPhone" placeholder="Contoh: 0812..."
                                class="w-full px-6 py-4 rounded-2xl border border-slate-100 bg-slate-50 focus:bg-white focus:border-[#006d5b] focus:ring-0 transition font-bold text-sm">
                        </div>
                    </div>
                </section>

                <!-- Shipping Address -->
                <section class="bg-white p-6 lg:p-10 shadow-2xl shadow-slate-200/50 rounded-[2.5rem] border border-slate-50">
                    <div class="flex items-center gap-4 mb-8">
                        <div
                            class="w-12 h-12 bg-[#006d5b]/10 text-[#006d5b] rounded-2xl flex items-center justify-center shadow-inner">
                            <i class="ti ti-map-pin-2 text-2xl"></i>
                        </div>
                        <h2 class="text-xl font-black uppercase tracking-tight text-slate-900">Alamat Pengiriman</h2>
                    </div>

                    <div class="grid grid-cols-1 gap-8">
                        <div class="relative">
                            <label
                                class="text-[10px] font-black uppercase text-slate-400 tracking-widest mb-3 block">Cari
                                Kecamatan / Kota</label>
                            <div class="relative">
                                <input type="text" x-model="areaSearch" @input.debounce.500ms="searchArea()"
                                    placeholder="Ketik minimal 3 huruf..."
                                    class="w-full px-6 py-4 rounded-2xl border border-slate-100 bg-slate-50 focus:bg-white focus:border-[#006d5b] focus:ring-0 transition font-bold text-sm">
                                <i class="ti ti-search absolute right-6 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            </div>

                            <!-- Search Results Dropdown -->
                            <div x-show="areaResults.length > 0"
                                class="absolute left-0 right-0 mt-3 bg-white rounded-[2rem] shadow-2xl border border-slate-100 z-50 max-h-72 overflow-y-auto no-scrollbar py-2"
                                x-cloak @click.away="areaResults = []">
                                <template x-for="area in areaResults" :key="area.id">
                                    <div @click="selectArea(area)"
                                        class="px-6 py-4 hover:bg-slate-50 cursor-pointer border-b border-slate-50 last:border-0 flex flex-col group transition text-left">
                                        <span class="text-xs font-black text-slate-900 group-hover:text-[#006d5b]"
                                            x-text="area.name"></span>
                                        <span class="text-[9px] text-slate-400 font-bold uppercase mt-1"
                                            x-text="area.formatted_address"></span>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <div x-show="selectedArea" x-cloak
                            class="bg-[#006d5b]/5 p-5 rounded-2xl border-2 border-dashed border-[#006d5b]/20 flex items-center justify-between animate-fade-in shadow-inner">
                            <div class="flex items-center gap-4">
                                <div class="w-8 h-8 bg-[#006d5b] text-white rounded-lg flex items-center justify-center">
                                    <i class="ti ti-check text-lg"></i>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-xs font-black text-[#006d5b] uppercase tracking-widest"
                                        x-text="selectedArea?.name"></span>
                                    <span class="text-[9px] font-bold text-slate-400 mt-0.5"
                                        x-text="selectedArea?.postcode"></span>
                                </div>
                            </div>
                            <button @click="selectedArea = null; areaSearch = ''; courierRates = []"
                                class="w-8 h-8 rounded-lg hover:bg-red-50 text-slate-400 hover:text-red-500 transition-all flex items-center justify-center">
                                <i class="ti ti-x text-sm"></i>
                            </button>
                        </div>

                        <div>
                            <label
                                class="text-[10px] font-black uppercase text-slate-400 tracking-widest mb-3 block">Alamat
                                Lengkap & Patokan</label>
                            <textarea x-model="addressDetail" placeholder="Nama Jalan, No. Rumah, RT/RW, Patokan..."
                                class="w-full px-6 py-4 rounded-2xl border border-slate-100 bg-slate-50 focus:bg-white focus:border-[#006d5b] focus:ring-0 transition font-bold text-sm min-h-[120px]"></textarea>
                        </div>
                    </div>
                </section>

                <!-- Shipping Method -->
                <section class="bg-white p-6 lg:p-10 shadow-2xl shadow-slate-200/50 rounded-[2.5rem] border border-slate-50"
                    x-show="selectedArea" x-cloak x-transition>
                    <div class="flex items-center gap-4 mb-8">
                        <div
                            class="w-12 h-12 bg-emerald-50 text-[#006d5b] rounded-2xl flex items-center justify-center shadow-inner">
                            <i class="ti ti-truck-delivery text-2xl"></i>
                        </div>
                        <h2 class="text-xl font-black uppercase tracking-tight text-slate-900">Metode Pengiriman</h2>
                    </div>

                    <div x-show="loadingRates" class="flex flex-col items-center py-16">
                        <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-t-2 border-[#006d5b] mb-4">
                        </div>
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] animate-pulse">Menghitung
                            Ongkir...</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4" x-show="!loadingRates && courierRates.length > 0">
                        <template x-for="rate in courierRates" :key="rate.courier_code + rate.courier_service_code">
                            <div @click="selectCourier(rate)"
                                class="courier-card p-5 rounded-[2rem] bg-white border-2 cursor-pointer shadow-sm relative group overflow-hidden transition-all duration-300"
                                :class="selectedRate?.courier_service_code === rate.courier_service_code ? 'border-[#006d5b] bg-[#006d5b]/5 scale-[1.02]' : 'border-slate-50 hover:border-slate-200'">
                                <div class="flex items-center justify-between mb-4 relative z-10">
                                    <div class="flex items-center gap-3 text-left">
                                        <div
                                            class="w-10 h-10 bg-white rounded-xl flex items-center justify-center p-1.5 border border-slate-100 shadow-sm">
                                            <img :src="rate.courier_logo" class="w-full h-full object-contain"
                                                :alt="rate.courier_name">
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-[11px] font-black uppercase text-slate-900"
                                                x-text="rate.courier_name"></span>
                                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter"
                                                x-text="rate.courier_service_name"></span>
                                        </div>
                                    </div>
                                    <span
                                        class="text-xs font-black text-[#006d5b] bg-emerald-50 px-3 py-1.5 rounded-xl border border-emerald-100/50"
                                        x-text="formatPrice(rate.price)"></span>
                                </div>
                                <div class="flex items-center gap-2 relative z-10">
                                    <i class="ti ti-clock text-slate-400 text-xs"></i>
                                    <span class="text-[9px] font-bold text-slate-500 uppercase tracking-widest text-left"
                                        x-text="'Estimasi: ' + rate.duration"></span>
                                </div>

                                <div x-show="selectedRate?.courier_service_code === rate.courier_service_code"
                                    class="absolute -right-2 -bottom-2 text-emerald-500/10 pointer-events-none">
                                    <i class="ti ti-circle-check-filled text-[60px]"></i>
                                </div>
                            </div>
                        </template>
                    </div>
                </section>
            </div>

            <!-- Right: Order Summary -->
            <div class="w-full lg:w-1/3">
                <div class="sticky top-28 flex flex-col gap-6">
                    <section class="bg-white p-8 lg:p-10 shadow-2xl shadow-slate-200/50 rounded-[2.5rem] border border-slate-50">
                        <h2
                            class="text-xs font-black uppercase tracking-[0.2em] text-slate-400 mb-8 border-b border-slate-50 pb-4">
                            Ringkasan Pesanan</h2>

                        <!-- Cart Items Mini View -->
                        <div class="flex flex-col gap-5 mb-8 max-h-60 overflow-y-auto no-scrollbar pr-2">
                            @foreach($cart as $id => $item)
                            <div class="flex gap-4 group">
                                <div
                                    class="w-14 h-14 rounded-2xl overflow-hidden bg-slate-50 shrink-0 border border-slate-100 shadow-sm transition group-hover:scale-105">
                                    @php
                                    $img = $item['image'] ? (Str::startsWith($item['image'], 'http') ? $item['image'] :
                                    asset('storage/'.$item['image'])) : asset('assets/img/elements/1.jpg');
                                    @endphp
                                    <img src="{{ $img }}" class="w-full h-full object-cover">
                                </div>
                                <div class="flex flex-col justify-center flex-1 text-left min-w-0">
                                    <h3 class="text-[10px] font-black uppercase text-slate-800 truncate leading-tight">{{ $item['name'] }}</h3>
                                    <div class="flex items-center justify-between mt-1.5">
                                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">{{ $item['quantity']
                                            }}x @ Rp {{ number_format($item['price'], 0, ',', '.') }}</span>
                                        <span class="text-[10px] font-black text-slate-900 italic">Rp {{
                                            number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="flex flex-col gap-4 mb-8">
                            <div
                                class="flex justify-between items-center text-[11px] font-bold text-slate-500 uppercase tracking-widest">
                                <span>Subtotal</span>
                                <span class="text-slate-900 font-black italic">Rp {{ number_format($totalPrice, 0, ',',
                                    '.') }}</span>
                            </div>
                            <div
                                class="flex justify-between items-center text-[11px] font-bold text-slate-500 uppercase tracking-widest">
                                <span>Ongkos Kirim</span>
                                <span class="text-[#006d5b] font-black italic"
                                    x-text="selectedRate ? formatPrice(selectedRate.price) : 'Rp 0'"></span>
                            </div>
                        </div>

                        <div
                            class="flex justify-between items-center mb-10 bg-slate-50/50 p-6 rounded-3xl border border-slate-100 shadow-inner">
                            <span class="text-xs font-black uppercase tracking-widest text-slate-900">Total Akhir</span>
                            <span class="text-2xl font-black text-[#006d5b] italic tracking-tighter"
                                x-text="formatPrice({{ $totalPrice }} + (selectedRate ? selectedRate.price : 0))"></span>
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
                                class="w-full py-5 rounded-[2rem] bg-[#006d5b] text-white font-black text-[13px] uppercase tracking-[0.2em] shadow-xl shadow-emerald-900/20 disabled:grayscale disabled:opacity-50 disabled:cursor-not-allowed group transition hover:bg-[#004d40] active:scale-95 flex items-center justify-center gap-3">
                                BUAT PESANAN <i class="ti ti-arrow-right group-hover:translate-x-1 transition"></i>
                            </button>
                        </form>

                        <div class="mt-8 pt-8 border-t border-slate-50 flex flex-col items-center gap-4">
                            <p class="text-[9px] text-center text-slate-400 font-black uppercase tracking-[0.2em] flex items-center gap-2">
                                <i class="ti ti-lock text-[#006d5b] text-base"></i> Transaksi Aman & Terenkripsi
                            </p>
                        </div>
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
                                'Content-Type': 'application/json', // Fixed header
                                'Accept': 'application/json',
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