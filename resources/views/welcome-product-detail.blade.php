<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $product->name }} | {{ config('app.name', 'Laravel') }}</title>

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

        .btn-primary-teal {
            background: linear-gradient(135deg, #006d5b 0%, #004d40 100%);
            color: white;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: none;
        }

        .btn-primary-teal:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 10px 25px rgba(0, 109, 91, 0.3);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        [x-cloak] {
            display: none !important;
        }

        .gallery-main {
            /* Rasio 1:1 di mobile agar pas, dan kita limit maksimalnya di desktop */
            aspect-ratio: 4 / 5;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 20px 50px -12px rgba(0, 0, 0, 0.1);
            position: relative;
            background-color: transparent;
            /* Pastikan transparan agar tidak ada tepi putih */
        }

        @media (min-width: 1024px) {
            .gallery-main {
                /* Di desktop sedikit lebih tinggi tapi ada max-height agar info sebelah kanan tetap naik */
                aspect-ratio: 4 / 5;
                max-height: 480px;
            }
        }

        .gallery-main img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            /* Ini yang membuat foto menutupi frame penuh tanpa ruang putih */
            object-position: center;
        }

        .whatsapp-float {
            position: fixed;
            bottom: 80px;
            right: 30px;
            width: 60px;
            height: 60px;
            background-color: #25d366;
            color: #fff;
            border-radius: 50px;
            text-align: center;
            font-size: 30px;
            box-shadow: 0 10px 25px rgba(37, 211, 102, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 100;
            transition: all 0.3s ease;
        }

        .whatsapp-float:hover {
            transform: scale(1.1) rotate(5deg);
            background-color: #128c7e;
        }

        .thumb-active {
            border: 3px solid #006d5b;
            transform: scale(1.1);
            z-index: 10;
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .premium-badge {
            background: linear-gradient(90deg, #006d5b, #009688);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 900;
        }
    </style>
</head>

<body class="antialiased" x-data="{ 
    activeImg: '{{ (is_array($product->images) && count($product->images) > 0) ? (Str::startsWith($product->images[0], 'http') ? $product->images[0] : asset('storage/'.$product->images[0])) : asset('assets/img/elements/1.jpg') }}',
    qty: 1,
    selectedV1: null,
    selectedV2: null,
    basePrice: {{ $product->price }},
    variantPrices: {}, // Placeholder for future variant pricing data
    get currentPrice() {
        // Logic for specific variant price can be added here
        return this.basePrice;
    },
    formatRupiah(num) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(num);
    }
}">

    <header class="bg-white/80 backdrop-blur-md sticky top-0 z-50 border-bottom">
        <div class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between">
            <a href="/" class="flex items-center gap-2 group">
                <i class="ti ti-arrow-left text-slate-800 group-hover:-translate-x-2 transition"></i>
                <span class="font-bold text-slate-800">Kembali</span>
            </a>
            <div class="flex items-center gap-4">
                <i class="ti ti-share text-slate-600 cursor-pointer hover:text-primary"></i>
                <i class="ti ti-heart text-slate-600 cursor-pointer hover:text-red-500"></i>
            </div>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-4 py-6 lg:py-10">
        <div class="flex flex-col lg:flex-row gap-8 lg:gap-10 items-start">

            <div class="w-full lg:w-[40%]">
                <div class="sticky top-24">
                    <div class="gallery-main mb-4 border border-slate-200/50">
                        <img :src="activeImg" class="w-full h-full object-cover transition duration-700 ease-out"
                            id="main-img">
                        @if($product->is_active)
                        <div class="absolute top-4 left-4">
                            <span
                                class="bg-white/90 backdrop-blur-sm shadow-sm text-[#006d5b] text-[9px] font-black px-3 py-1.5 rounded-full uppercase tracking-tighter">
                                <i class="ti ti-discount-check-filled me-1"></i> Original
                            </span>
                        </div>
                        @endif
                    </div>

                    @if(is_array($product->images) && count($product->images) > 1)
                    <div class="flex gap-3 overflow-x-auto pb-2 no-scrollbar">
                        @foreach($product->images as $img)
                        @php $src = Str::startsWith($img, 'http') ? $img : asset('storage/'.$img); @endphp
                        <div class="shrink-0 w-16 h-16 rounded-xl overflow-hidden cursor-pointer bg-slate-100 shadow-sm transition border border-slate-200"
                            :class="activeImg === '{{ $src }}' ? 'thumb-active' : 'hover:opacity-75'"
                            @click="activeImg = '{{ $src }}'">
                            <img src="{{ $src }}" class="w-full h-full object-cover">
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>

            <div class="w-full lg:w-[60%] flex flex-col">
                <div class="mb-3">
                    <span
                        class="bg-[#006d5b]/10 text-[#006d5b] text-[10px] font-black px-3 py-1.5 rounded-full uppercase tracking-widest border border-[#006d5b]/20">
                        {{ $product->category->name }}
                    </span>
                </div>

                <h1 class="text-xl lg:text-2xl font-black text-slate-900 mb-4 leading-tight uppercase tracking-tight">
                    {{ $product->name }}
                </h1>

                <div class="flex items-center gap-4 mb-6">
                    <div class="flex items-center bg-yellow-400/15 px-2 py-1.5 rounded-lg border border-yellow-400/20">
                        <i class="ti ti-star-filled text-yellow-500 mr-1.5 text-sm"></i>
                        <span class="font-black text-slate-800 text-sm">{{ $product->rating ?? '5.0' }}</span>
                    </div>
                    <div class="h-4 w-[1px] bg-slate-300"></div>
                    <span class="text-slate-600 text-xs font-bold"><span class="text-slate-900">{{ $product->sold_count
                            ?? '1.2k' }}</span> Terjual</span>
                </div>

                <div class="glass-card p-5 lg:p-6 rounded-[24px] shadow-xl shadow-slate-200/50 mb-6 border-0">
                    <div class="flex flex-col mb-4">
                        <div class="flex items-center gap-3 flex-wrap">
                            <span class="text-2xl lg:text-3xl font-black text-[#006d5b] tracking-tighter"
                                x-text="formatRupiah(currentPrice)"></span>
                            @if($product->old_price)
                            <div class="flex items-center gap-2">
                                <span class="text-sm text-slate-400 line-through decoration-red-500/50">Rp {{
                                    number_format($product->old_price, 0, ',', '.') }}</span>
                                @php $disc = round((($product->old_price - $product->price) / $product->old_price) *
                                100); @endphp
                                <span class="bg-red-500 text-white text-[9px] font-black px-1.5 py-0.5 rounded-lg">-{{
                                    $disc }}%</span>
                            </div>
                            @endif
                        </div>
                        <p class="text-[10px] text-slate-500 font-medium mt-1.5 flex items-center gap-1">
                            <i class="ti ti-shield-check text-green-500"></i> Harga kompetitif & Terjamin
                        </p>
                    </div>

                    <hr class="my-4 border-slate-100">

                    @if($product->variant_1_name && $product->variant_1_options)
                    <div class="mb-4">
                        <label class="block text-[9px] font-black uppercase text-slate-400 tracking-widest mb-3">{{
                            $product->variant_1_name }}</label>
                        <div class="flex flex-wrap gap-2">
                            @foreach($product->variant_1_options as $opt)
                            <button @click="selectedV1 = '{{ $opt }}'"
                                :class="selectedV1 === '{{ $opt }}' ? 'border-[#006d5b] text-[#006d5b] bg-[#006d5b]/5 shadow-sm' : 'border-slate-200 text-slate-600'"
                                class="px-4 py-2 rounded-xl border-2 text-xs font-bold hover:border-[#006d5b] hover:text-[#006d5b] transition-all duration-300">
                                {{ $opt }}
                            </button>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    @if($product->variant_2_name && $product->variant_2_options)
                    <div class="mb-6">
                        <label class="block text-[9px] font-black uppercase text-slate-400 tracking-widest mb-3">{{
                            $product->variant_2_name }}</label>
                        <div class="flex flex-wrap gap-2">
                            @foreach($product->variant_2_options as $opt)
                            <button @click="selectedV2 = '{{ $opt }}'"
                                :class="selectedV2 === '{{ $opt }}' ? 'border-[#006d5b] text-[#006d5b] bg-[#006d5b]/5 shadow-sm' : 'border-slate-200 text-slate-600'"
                                class="px-4 py-2 rounded-xl border-2 text-xs font-bold hover:border-[#006d5b] hover:text-[#006d5b] transition-all duration-300">
                                {{ $opt }}
                            </button>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <div class="flex items-center gap-3 lg:gap-4">
                        <div
                            class="flex items-center border border-slate-200 rounded-xl p-1 bg-white/50 backdrop-blur-sm shadow-sm shrink-0">
                            <button @click="if(qty > 1) qty--"
                                class="w-8 h-8 lg:w-10 lg:h-10 flex items-center justify-center hover:bg-slate-100 rounded-lg transition"><i
                                    class="ti ti-minus text-xs lg:text-base"></i></button>
                            <span class="w-8 text-center font-black text-sm lg:text-base" x-text="qty"></span>
                            <button @click="qty++"
                                class="w-8 h-8 lg:w-10 lg:h-10 flex items-center justify-center hover:bg-slate-100 rounded-lg transition"><i
                                    class="ti ti-plus text-xs lg:text-base"></i></button>
                        </div>

                        <form action="{{ route('checkout.index') }}" method="GET" class="flex-1">
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="qty" :value="qty">
                            <input type="hidden" name="variant_1" :value="selectedV1">
                            <input type="hidden" name="variant_2" :value="selectedV2">
                            <button type="submit"
                                :disabled="@if($product->variant_1_name) !selectedV1 @endif @if($product->variant_2_name) || !selectedV2 @endif"
                                class="w-full btn-primary-teal h-10 lg:h-12 rounded-xl font-black text-[10px] lg:text-xs uppercase tracking-widest shadow-lg shadow-[#006d5b]/20 flex items-center justify-center disabled:opacity-50 disabled:grayscale">
                                <i class="ti ti-shopping-cart-plus me-2 text-sm lg:text-base"></i> Checkout Sekarang
                            </button>
                        </form>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div
                        class="p-4 bg-white/60 backdrop-blur-sm rounded-[20px] border border-white shadow-sm flex items-center gap-3">
                        <div
                            class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center shrink-0">
                            <i class="ti ti-scale-outline ti-sm"></i>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">Weight</span>
                            <span class="text-xs font-black">{{ $product->weight }} gr</span>
                        </div>
                    </div>
                    <div
                        class="p-4 bg-white/60 backdrop-blur-sm rounded-[20px] border border-white shadow-sm flex items-center gap-3">
                        <div
                            class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center shrink-0">
                            <i class="ti ti-building-warehouse ti-sm"></i>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">Stock</span>
                            <span
                                class="text-xs font-black {{ $product->stock < 5 ? 'text-rose-500' : 'text-emerald-600' }}">{{
                                $product->stock }} unit</span>
                        </div>
                    </div>
                </div>

                <div class="prose prose-slate max-w-none">
                    <div class="flex items-center gap-3 mb-4">
                        <h4 class="text-[10px] font-black uppercase text-slate-400 tracking-[0.1em]">Deskripsi Produk
                        </h4>
                        <div class="h-px flex-1 bg-slate-200"></div>
                    </div>
                    <div class="bg-white/40 backdrop-blur-sm p-5 lg:p-6 rounded-[24px] border border-white shadow-sm">
                        <p
                            class="text-slate-600 text-[13px] lg:text-[14px] leading-relaxed whitespace-pre-wrap font-medium">
                            {{ $product->description ?? 'Tidak ada deskripsi untuk produk ini.' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        @if($relatedProducts->count() > 0)
        <section class="mt-20">
            <div class="flex flex-col items-center mb-10">
                <h3 class="text-xl lg:text-2xl font-black uppercase tracking-tighter mb-2">Mungkin Kamu Suka</h3>
                <div class="w-10 h-1 bg-[#006d5b] rounded-full"></div>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                @foreach($relatedProducts as $rel)
                <a href="{{ route('product.detail', $rel->slug) }}"
                    class="bg-white rounded-[24px] overflow-hidden shadow-sm hover:shadow-xl transition-all duration-500 group border border-slate-50">
                    <div class="aspect-[4/5] relative overflow-hidden bg-transparent">
                        @php
                        $rImg = is_array($rel->images) && count($rel->images) > 0 ? $rel->images[0] : null;
                        $rSrc = $rImg ? (Str::startsWith($rImg, 'http') ? $rImg : asset('storage/'.$rImg)) :
                        asset('assets/img/elements/1.jpg');
                        @endphp
                        <img src="{{ $rSrc }}"
                            class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                        <div class="absolute bottom-3 left-3">
                            <span
                                class="bg-white/90 backdrop-blur-sm text-[8px] font-black uppercase px-2 py-1 rounded-md shadow-sm">{{
                                $rel->category->name }}</span>
                        </div>
                    </div>
                    <div class="p-5">
                        <h4 class="text-[11px] font-bold uppercase truncate mb-1.5 text-slate-700">{{ $rel->name }}</h4>
                        <div class="flex items-center justify-between">
                            <span class="text-[#006d5b] font-black text-sm">Rp {{ number_format($rel->price, 0, ',',
                                '.') }}</span>
                            <i
                                class="ti ti-chevron-right text-slate-300 group-hover:text-[#006d5b] text-sm transition"></i>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </section>
        @endif

    </main>

    <footer class="bg-slate-900 text-white pt-16 pb-8 mt-16">
        <div class="max-w-6xl mx-auto px-4 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="text-xl font-black">
                <span class="text-[#006d5b]">TOKO</span><span>KITA</span>
            </div>
            <p class="text-slate-500 text-[9px] font-black uppercase tracking-[0.3em]">Premium Shopping Experience</p>
            <div class="flex gap-5">
                <i class="ti ti-brand-instagram text-lg text-slate-400 hover:text-white cursor-pointer transition"></i>
                <i class="ti ti-brand-facebook text-lg text-slate-400 hover:text-white cursor-pointer transition"></i>
                <i class="ti ti-brand-whatsapp text-lg text-slate-400 hover:text-white cursor-pointer transition"></i>
            </div>
        </div>
        <div class="max-w-6xl mx-auto px-4 mt-10 pt-6 border-t border-white/5 text-center">
            <p class="text-slate-600 text-[9px] font-bold uppercase tracking-widest">© 2026 Toko Kita Premium
                E-commerce. All Rights Reserved.</p>
        </div>
    </footer>

    <a href="https://wa.me/6281234567890?text=Halo, saya tertarik dengan produk {{ $product->name }}" target="_blank"
        class="whatsapp-float group">
        <i class="ti ti-brand-whatsapp"></i>
        <span
            class="absolute right-20 bg-white text-slate-800 text-[10px] font-bold px-4 py-2 rounded-xl shadow-xl opacity-0 group-hover:opacity-100 transition whitespace-nowrap hidden lg:block border">Chat
            Customer Service</span>
    </a>

    @include('layouts.bottom-nav')
</body>

</html>