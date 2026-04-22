<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detail Pesanan | {{ config('app.name', 'Laravel') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700;900&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/tabler-icons.css') }}" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Public Sans', sans-serif; background-color: #f7f7f7; color: #333; }
        .text-shopee { color: #fe2c22; }
        .bg-shopee { background-color: #fe2c22; }
    </style>
</head>
<body class="antialiased pb-10">

    <header class="bg-white sticky top-0 z-50 border-b shadow-sm">
        <div class="max-w-[700px] mx-auto px-4 py-4 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('account.orders') }}" class="text-slate-600">
                    <i class="ti ti-arrow-left text-xl"></i>
                </a>
                <h1 class="text-base font-bold">Rincian Pesanan</h1>
            </div>
            <span class="text-[11px] font-bold uppercase text-shopee">
                {{ ucfirst($transaction->status) }}
            </span>
        </div>
    </header>

    <main class="max-w-[700px] mx-auto space-y-3 pt-3">
        
        <!-- Status & Tracking Card -->
        <div class="bg-white p-5 border-b shadow-sm">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-8 h-8 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center">
                    <i class="ti ti-truck-delivery"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-[13px] font-bold">Informasi Pengiriman</h3>
                    <p class="text-[11px] text-slate-500 uppercase">{{ $transaction->shipping_courier }} - {{ $transaction->shipping_service }}</p>
                </div>
            </div>
            
            <div class="pl-11 border-l-2 border-slate-100 ml-4 space-y-6">
                <div class="relative">
                    <div class="absolute -left-[1.65rem] top-1 w-3 h-3 rounded-full bg-emerald-500 ring-4 ring-emerald-100"></div>
                    <p class="text-[12px] font-medium text-emerald-600">
                        @if($transaction->status == 'pending') Menunggu Pembayaran
                        @elseif($transaction->status == 'paid') Pesanan sedang dikemas
                        @elseif($transaction->status == 'shipping') Paket telah diserahkan ke kurir
                        @elseif($transaction->status == 'completed') Paket telah diterima
                        @endif
                    </p>
                    <p class="text-[10px] text-slate-400 mt-1">{{ $transaction->updated_at->format('d M Y, H:i') }}</p>
                </div>
                @if($transaction->shipping_waybill)
                <div class="flex items-center justify-between bg-slate-50 p-3 rounded-lg border border-slate-100">
                    <div class="flex flex-col">
                        <span class="text-[10px] text-slate-400 font-bold uppercase">No. Resi</span>
                        <span class="text-[12px] font-mono font-bold text-slate-700">{{ $transaction->shipping_waybill }}</span>
                    </div>
                    <button onclick="navigator.clipboard.writeText('{{ $transaction->shipping_waybill }}')" class="text-xs font-bold text-shopee uppercase">Salin</button>
                </div>
                @endif
            </div>
        </div>

        <!-- Address Card -->
        <div class="bg-white p-5 border-b shadow-sm">
            <div class="flex items-center gap-3 mb-4">
                <i class="ti ti-map-pin text-slate-400"></i>
                <h3 class="text-[13px] font-bold">Alamat Pengiriman</h3>
            </div>
            <div class="pl-7">
                <p class="text-[13px] font-bold text-slate-800 mb-1">{{ $transaction->shipping_name }}</p>
                <p class="text-[12px] text-slate-500 mb-2">{{ $transaction->shipping_phone }}</p>
                <p class="text-[12px] text-slate-600 leading-relaxed">
                    {{ $transaction->shipping_address }}<br>
                    <span class="font-medium text-slate-400 uppercase tracking-tighter">Pos: {{ $transaction->shipping_postal_code }}</span>
                </p>
            </div>
        </div>

        <!-- Items Card -->
        <div class="bg-white border-b shadow-sm">
            @foreach($transaction->details as $detail)
            <div class="p-5 flex gap-4 {{ !$loop->last ? 'border-b border-slate-50' : '' }}">
                <div class="w-16 h-16 rounded bg-slate-50 border shrink-0">
                    @php
                        $img = $detail->product->image ? (Str::startsWith($detail->product->image, 'http') ? $detail->product->image : asset('storage/'.$detail->product->image)) : asset('assets/img/elements/1.jpg');
                    @endphp
                    <img src="{{ $img }}" class="w-full h-full object-cover">
                </div>
                <div class="flex-1 min-w-0">
                    <h4 class="text-[13px] text-slate-800 line-clamp-2 leading-snug mb-1">{{ $detail->product->name }}</h4>
                    <p class="text-[11px] text-slate-400 mb-2">Variasi: {{ $detail->variant_2 ?: 'Standard' }}</p>
                    <div class="flex justify-between items-center">
                        <span class="text-[12px] text-slate-600">x{{ $detail->quantity }}</span>
                        <span class="text-[13px] font-medium">Rp{{ number_format($detail->price, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Summary Card -->
        <div class="bg-white p-5 border-b shadow-sm space-y-3">
            <div class="flex justify-between text-[13px]">
                <span class="text-slate-500">Subtotal Produk</span>
                <span>Rp{{ number_format($transaction->total_price, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-[13px]">
                <span class="text-slate-500">Subtotal Pengiriman</span>
                <span>Rp{{ number_format($transaction->shipping_price, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-[14px] font-bold pt-3 border-t">
                <span>Total Pesanan</span>
                <span class="text-shopee">Rp{{ number_format($transaction->grand_total, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Payment Info Card -->
        <div class="bg-white p-5 border-b shadow-sm mb-20">
            <div class="flex items-center gap-3 mb-4">
                <i class="ti ti-credit-card text-slate-400"></i>
                <h3 class="text-[13px] font-bold">Informasi Pembayaran</h3>
            </div>
            <div class="space-y-4 pl-7">
                <div class="flex justify-between text-[12px]">
                    <span class="text-slate-500">No. Pesanan</span>
                    <span class="font-mono text-slate-800 font-bold">{{ $transaction->code }}</span>
                </div>
                <div class="flex justify-between text-[12px]">
                    <span class="text-slate-500">Waktu Pemesanan</span>
                    <span>{{ $transaction->created_at->format('d-m-Y H:i') }}</span>
                </div>
                
                @if($transaction->status == 'pending' && $transaction->payment_link)
                <div class="pt-4">
                    <a href="{{ $transaction->payment_link }}" target="_blank" class="w-full inline-block text-center py-3 bg-shopee text-white text-[12px] font-bold rounded-sm shadow-md">
                        BAYAR SEKARANG
                    </a>
                </div>
                @endif
            </div>
        </div>

    </main>

    @include('layouts.bottom-nav')

</body>
</html>
