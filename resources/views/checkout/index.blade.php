<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Checkout | {{ config('app.name', 'Laravel') }}</title>

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
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 24px;
        }

        .btn-primary-teal {
            background: #006d5b;
            color: white;
            transition: all 0.3s ease;
        }

        .btn-primary-teal:hover {
            background: #005647;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(0, 109, 91, 0.4);
        }

        .courier-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 2px solid transparent;
        }

        .courier-card.selected {
            border-color: #006d5b;
            background: rgba(0, 109, 91, 0.05);
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
    </style>
</head>

<body class="antialiased" x-data="checkoutPage()">
    <header class="bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-slate-100">
        <div class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between">
            <a href="/" class="flex items-center gap-2">
                <span class="text-xl font-black italic uppercase tracking-tighter text-slate-900">
                    <span class="text-[#006d5b]">Toko</span>Kita
                </span>
            </a>
            <div class="flex items-center gap-3">
                <div class="flex flex-col items-end">
                    <span class="text-[10px] font-black uppercase text-slate-400">Secure Checkout</span>
                    <span class="text-xs font-bold text-emerald-600 flex items-center gap-1">
                        <i class="ti ti-shield-lock"></i> SSL Encrypted
                    </span>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-4 py-8 lg:py-12">
        <div class="flex flex-col lg:flex-row gap-10">
            <!-- Left: Form -->
            <div class="w-full lg:w-2/3 flex flex-col gap-8">
                <!-- Shipping Address -->
                <section class="glass-card p-6 lg:p-10 shadow-xl shadow-slate-200/50">
                    <div class="flex items-center gap-4 mb-8">
                        <div
                            class="w-10 h-10 bg-[#006d5b]/10 text-[#006d5b] rounded-xl flex items-center justify-center">
                            <i class="ti ti-map-pin-2 text-xl"></i>
                        </div>
                        <h2 class="text-xl font-black uppercase tracking-tight text-slate-900">Shipping Address</h2>
                    </div>

                    <div class="grid grid-cols-1 gap-6">
                        <div class="relative">
                            <label
                                class="text-[10px] font-black uppercase text-slate-400 tracking-widest mb-2 block">Search
                                District/City</label>
                            <input type="text" x-model="areaSearch" @input.debounce.500ms="searchArea()"
                                placeholder="Type city or district..."
                                class="w-full px-5 py-3 rounded-xl border border-slate-200 focus:border-[#006d5b] focus:ring-0 transition font-medium">

                            <!-- Search Results Dropdown -->
                            <div x-show="areaResults.length > 0"
                                class="absolute left-0 right-0 mt-2 bg-white rounded-2xl shadow-2xl border border-slate-100 z-50 max-h-60 overflow-y-auto no-scrollbar"
                                @click.away="areaResults = []">
                                <template x-for="area in areaResults" :key="area.id">
                                    <div @click="selectArea(area)"
                                        class="px-5 py-3 hover:bg-slate-50 cursor-pointer border-b border-slate-50 last:border-0 flex flex-col">
                                        <span class="text-sm font-black text-slate-900" x-text="area.name"></span>
                                        <span class="text-[10px] text-slate-400" x-text="area.formatted_address"></span>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <div x-show="selectedArea"
                            class="bg-emerald-50 p-4 rounded-xl border border-emerald-100 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <i class="ti ti-circle-check-filled text-emerald-500"></i>
                                <span class="text-xs font-bold text-emerald-800" x-text="selectedArea?.name"></span>
                            </div>
                            <button @click="selectedArea = null; areaSearch = ''; courierRates = []"
                                class="text-[10px] font-black uppercase text-emerald-600 hover:underline">Change</button>
                        </div>

                        <div>
                            <label
                                class="text-[10px] font-black uppercase text-slate-400 tracking-widest mb-2 block">Detailed
                                Address</label>
                            <textarea x-model="addressDetail" placeholder="Street name, house number, etc."
                                class="w-full px-5 py-3 rounded-xl border border-slate-200 focus:border-[#006d5b] focus:ring-0 transition font-medium min-h-[100px]"></textarea>
                        </div>
                    </div>
                </section>

                <!-- Shipping Method -->
                <section class="glass-card p-6 lg:p-10 shadow-xl shadow-slate-200/50" x-show="selectedArea">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center">
                            <i class="ti ti-truck-delivery text-xl"></i>
                        </div>
                        <h2 class="text-xl font-black uppercase tracking-tight text-slate-900">Shipping Method</h2>
                    </div>

                    <div x-show="loadingRates" class="flex flex-col items-center py-10">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-[#006d5b] mb-4"></div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Calculating
                            prices...</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4"
                        x-show="!loadingRates && courierRates.length > 0">
                        <template x-for="rate in courierRates" :key="rate.courier_code + rate.courier_service_code">
                            <div @click="selectCourier(rate)"
                                class="courier-card p-5 rounded-2xl bg-white border-2 cursor-pointer shadow-sm"
                                :class="selectedRate?.courier_service_code === rate.courier_service_code ? 'selected' : 'border-slate-100 hover:border-slate-200'">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center gap-3">
                                        <img :src="rate.courier_logo" class="h-6 object-contain"
                                            :alt="rate.courier_name">
                                        <span class="text-xs font-black uppercase text-slate-900"
                                            x-text="rate.courier_name"></span>
                                    </div>
                                    <span class="text-xs font-black text-[#006d5b]"
                                        x-text="formatPrice(rate.price)"></span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-tighter"
                                        x-text="rate.courier_service_name"></span>
                                    <span class="text-[9px] text-slate-400"
                                        x-text="'Est. arrival: ' + rate.duration"></span>
                                </div>
                            </div>
                        </template>
                    </div>
                </section>
            </div>

            <!-- Right: Order Summary -->
            <div class="w-full lg:w-1/3">
                <div class="sticky top-28 flex flex-col gap-6">
                    <section class="glass-card p-8 shadow-xl shadow-slate-200/50">
                        <h2 class="text-sm font-black uppercase tracking-widest text-slate-900 mb-6">Order Summary</h2>

                        <!-- Mini Product Card -->
                        <div class="flex gap-4 mb-6 pb-6 border-b border-slate-100">
                            @php
                            $img = is_array($product->images) && count($product->images) > 0 ? $product->images[0] :
                            null;
                            $src = $img ? (Str::startsWith($img, 'http') ? $img : asset('storage/'.$img)) :
                            asset('assets/img/elements/1.jpg');
                            @endphp
                            <div class="w-20 h-20 rounded-xl overflow-hidden shadow-sm shrink-0">
                                <img src="{{ $src }}" class="w-full h-full object-cover">
                            </div>
                            <div class="flex flex-col justify-center">
                                <h3 class="text-xs font-black uppercase text-slate-900 line-clamp-1 truncate">{{
                                    $product->name }}</h3>
                                <p class="text-[10px] text-slate-400 font-bold uppercase mt-1">
                                    Qty: {{ $qty }}
                                    @if($variant1) • {{ $variant1 }} @endif
                                    @if($variant2) • {{ $variant2 }} @endif
                                </p>
                                <span class="text-sm font-black text-slate-900 mt-2">Rp {{ number_format($product->price
                                    * $qty, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div class="flex flex-col gap-3 mb-8">
                            <div class="flex justify-between items-center text-xs font-bold text-slate-500">
                                <span>Subtotal</span>
                                <span class="text-slate-900">Rp {{ number_format($product->price * $qty, 0, ',', '.')
                                    }}</span>
                            </div>
                            <div class="flex justify-between items-center text-xs font-bold text-slate-500">
                                <span>Shipping Cost</span>
                                <span class="text-slate-900"
                                    x-text="selectedRate ? formatPrice(selectedRate.price) : 'Rp 0'"></span>
                            </div>
                        </div>

                        <div
                            class="flex justify-between items-center mb-8 bg-slate-50 p-4 rounded-xl border border-slate-100">
                            <span class="text-xs font-black uppercase tracking-widest text-slate-900">Total Grand</span>
                            <span class="text-xl font-black text-[#006d5b]"
                                x-text="formatPrice({{ $product->price * $qty }} + (selectedRate ? selectedRate.price : 0))"></span>
                        </div>

                        <form action="{{ route('checkout.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="qty" value="{{ $qty }}">
                            <input type="hidden" name="variant_1" value="{{ $variant1 }}">
                            <input type="hidden" name="variant_2" value="{{ $variant2 }}">
                            <input type="hidden" name="shipping_area_id" :value="selectedArea?.id">
                            <input type="hidden" name="shipping_address" :value="addressDetail">
                            <input type="hidden" name="shipping_courier" :value="selectedRate?.courier_code">
                            <input type="hidden" name="shipping_service" :value="selectedRate?.courier_service_code">
                            <input type="hidden" name="shipping_price" :value="selectedRate?.price">
                            <input type="hidden" name="shipping_postal_code" :value="selectedArea?.postcode">

                            <button type="submit" :disabled="!selectedRate || !addressDetail"
                                class="w-full h-14 rounded-2xl btn-primary-teal font-black text-xs uppercase tracking-widest disabled:opacity-50 disabled:cursor-not-allowed">
                                Order Now
                            </button>
                        </form>

                        <p class="text-[9px] text-center text-slate-400 font-bold uppercase mt-6 tracking-widest">
                            <i class="ti ti-lock me-1"></i> Secure Transaction
                        </p>
                    </section>
                </div>
            </div>
        </div>
    </main>

    @include('layouts.bottom-nav')

    <script>
        function checkoutPage() {
            return {
                areaSearch: '{{ $initialPostal ?: ($initialLocationName ?: '') }}',
                areaResults: [],
                selectedArea: null,
                addressDetail: '{{ $initialAddress ?: '' }}',
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
                    try {
                        const res = await fetch('/shipping/rates', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                destination_area_id: this.selectedArea.id,
                                items: [{
                                    name: '{{ $product->name }}',
                                    description: 'E-commerce Item',
                                    value: {{ $product->price }},
                                    weight: {{ $product->weight }},
                                    quantity: {{ $qty }}
                                }]
                            })
                        });
                        const data = await res.json();
                        // Filter rates that have price and description
                        this.courierRates = (data.pricing || []).filter(r => r.price > 0);
                    } catch (e) {
                        console.error(e);
                    } finally {
                        this.loadingRates = false;
                    }
                },

                selectCourier(rate) {
                    this.selectedRate = rate;
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
</body>

</html>