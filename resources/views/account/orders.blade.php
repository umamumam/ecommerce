<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pesanan Saya | {{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/tabler-icons.css') }}" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Public Sans', sans-serif; background-color: #f7f7f7; color: #222; }
        .text-shopee { color: #fe2c22; }
        .bg-shopee { background-color: #fe2c22; }
        .tab-active { color: #fe2c22; border-bottom: 2px solid #fe2c22; }
        [x-cloak] { display: none !important; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
    </style>
</head>
<body class="antialiased pb-24">

    <!-- Header -->
    <header class="bg-white sticky top-0 z-50 border-b border-slate-100 shadow-sm">
        <div class="max-w-[700px] mx-auto px-4 py-4 flex items-center gap-4">
            <a href="{{ route('account') }}" class="text-slate-600">
                <i class="ti ti-arrow-left text-xl"></i>
            </a>
            <h1 class="text-base font-bold text-slate-800">Pesanan Saya</h1>
        </div>
    </header>

    <!-- Tabs (Shopee Style) -->
    <div class="bg-white sticky top-[60px] z-40 border-b overflow-x-auto no-scrollbar shadow-sm">
        <div class="max-w-[700px] mx-auto flex whitespace-nowrap px-2">
            <a href="{{ route('account.orders', ['status' => 'all']) }}" 
               class="flex-1 text-center py-4 text-[13px] font-medium transition {{ !$status || $status == 'all' ? 'tab-active' : 'text-slate-500' }}">
                Semua
            </a>
            <a href="{{ route('account.orders', ['status' => 'pending']) }}" 
               class="flex-1 text-center py-4 text-[13px] font-medium transition {{ $status == 'pending' ? 'tab-active' : 'text-slate-500' }}">
                Blm Bayar
            </a>
            <a href="{{ route('account.orders', ['status' => 'paid']) }}" 
               class="flex-1 text-center py-4 text-[13px] font-medium transition {{ $status == 'paid' ? 'tab-active' : 'text-slate-500' }}">
                Dikemas
            </a>
            <a href="{{ route('account.orders', ['status' => 'shipping']) }}" 
               class="flex-1 text-center py-4 text-[13px] font-medium transition {{ $status == 'shipping' ? 'tab-active' : 'text-slate-500' }}">
                Dikirim
            </a>
            <a href="{{ route('account.orders', ['status' => 'completed']) }}" 
               class="flex-1 text-center py-4 text-[13px] font-medium transition {{ $status == 'completed' ? 'tab-active' : 'text-slate-500' }}">
                Selesai
            </a>
        </div>
    </div>

    <!-- Order List -->
    <main class="max-w-[700px] mx-auto px-0 md:px-4 py-4 space-y-3">
        @forelse($transactions as $trx)
            <div class="bg-white shadow-sm border-t border-b md:border md:rounded-lg overflow-hidden group">
                <!-- Shop Header -->
                <div class="px-4 py-3 border-b flex items-center justify-between bg-white">
                    <div class="flex items-center gap-2">
                        <i class="ti ti-building-store text-shopee text-lg"></i>
                        <span class="text-[12px] font-bold uppercase tracking-tight">{{ config('app.name') }}</span>
                    </div>
                    <span class="text-[11px] font-bold uppercase {{ $trx->status == 'pending' ? 'text-amber-500' : ($trx->status == 'cancelled' ? 'text-slate-400' : 'text-emerald-500') }}">
                        @if($trx->status == 'pending') Belum Bayar
                        @elseif($trx->status == 'paid') Dikemas
                        @elseif($trx->status == 'shipping') Sedang Dikirim
                        @elseif($trx->status == 'completed') Selesai
                        @else {{ ucfirst($trx->status) }} @endif
                    </span>
                </div>

                <!-- Items Preview -->
                @php $firstDetail = $trx->details->first(); @endphp
                <div class="p-4 flex gap-4 cursor-pointer hover:bg-slate-50 transition">
                    <div class="w-16 h-16 rounded bg-slate-50 border shrink-0">
                        @php
                            $img = asset('assets/img/elements/1.jpg');
                            if ($firstDetail->product->image) {
                                $img = Str::startsWith($firstDetail->product->image, 'http') ? $firstDetail->product->image : asset('storage/'.$firstDetail->product->image);
                            } elseif ($firstDetail->product->images && is_array($firstDetail->product->images) && count($firstDetail->product->images) > 0) {
                                $firstImg = $firstDetail->product->images[0];
                                $img = Str::startsWith($firstImg, 'http') ? $firstImg : asset('storage/'.$firstImg);
                            }
                        @endphp
                        <img src="{{ $img }}" class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-[13px] text-slate-800 line-clamp-1 mb-1">{{ $firstDetail->product->name }}</h3>
                        <div class="flex justify-between items-center">
                            <span class="text-[11px] text-slate-400">Variasi: {{ $firstDetail->variant_2 ?: 'Standard' }}</span>
                            <span class="text-[12px] text-slate-600 uppercase">x{{ $firstDetail->quantity }}</span>
                        </div>
                        <div class="mt-2 text-right">
                            <span class="text-[13px] font-medium text-shopee">Rp{{ number_format($firstDetail->price, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <!-- More items indicator -->
                @if($trx->details->count() > 1)
                <div class="px-4 py-2 text-center border-t border-dashed">
                    <span class="text-[10px] text-slate-400 uppercase italic">Lihat {{ $trx->details->count() - 1 }} produk lainnya</span>
                </div>
                @endif

                <!-- Footer Summary & Actions -->
                <div class="px-4 py-4 border-t bg-slate-50/30">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-[11px] text-slate-400">{{ $trx->details->sum('quantity') }} Produk</span>
                        <div class="flex items-center gap-2">
                            <span class="text-[12px] text-slate-600">Total Pesanan:</span>
                            <span class="text-[14px] font-bold text-shopee">Rp{{ number_format($trx->grand_total, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 md:gap-3">
                        @if($trx->status == 'pending' && $trx->payment_link)
                            <a href="{{ $trx->payment_link }}" target="_blank" 
                               class="px-4 md:px-6 py-2 bg-[#fe2c22] text-white text-[11px] md:text-[12px] font-bold rounded-sm shadow-sm hover:opacity-90 transition flex items-center gap-2">
                                <i class="ti ti-wallet"></i>
                                BAYAR SEKARANG
                            </a>
                        @elseif($trx->status == 'shipping')
                            <button class="px-4 md:px-6 py-2 border border-slate-200 text-slate-600 text-[11px] md:text-[12px] font-medium rounded-sm hover:bg-slate-50 transition">
                                Lacak Paket
                            </button>
                        @endif
                        
                        <a href="{{ route('account.orders.show', $trx->id) }}" 
                           class="px-4 md:px-6 py-2 border border-slate-200 text-slate-600 text-[11px] md:text-[12px] font-medium rounded-sm hover:bg-slate-50 transition">
                            Detail Pesanan
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="py-24 text-center">
                <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="ti ti-receipt-off text-3xl text-slate-300"></i>
                </div>
                <h3 class="text-sm font-bold text-slate-800 mb-1">Belum Ada Pesanan</h3>
                <p class="text-[11px] text-slate-400 uppercase">Ayo mulai belanja dan penuhi gayamu!</p>
                <a href="{{ url('/') }}" class="mt-6 inline-block px-10 py-3 bg-shopee text-white text-[12px] font-bold rounded-sm shadow-md">
                    BELANJA SEKARANG
                </a>
            </div>
        @endforelse
    </main>

    @include('layouts.bottom-nav')

</body>
</html>
