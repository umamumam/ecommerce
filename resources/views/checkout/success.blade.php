<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Success | {{ config('app.name', 'Laravel') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700;900&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/tabler-icons.css') }}" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Public Sans', sans-serif; background-color: #f8fafc; }
        .success-card { background: white; border-radius: 32px; box-shadow: 0 40px 100px -20px rgba(0,0,0,0.05); }
    </style>
</head>

<body class="antialiased flex items-center justify-center min-h-screen p-2.5 sm:p-6">
    <div class="success-card max-w-2xl w-full p-5 sm:p-10 lg:p-12 flex flex-col items-center text-center">
        <div class="w-20 h-20 sm:w-24 sm:h-24 bg-emerald-50 text-emerald-500 rounded-full flex items-center justify-center mb-6 sm:mb-8 animate-bounce">
            <i class="ti ti-check text-4xl sm:text-5xl"></i>
        </div>
        
        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black text-slate-900 uppercase tracking-tight mb-3">Yeay! Order Placed</h1>
        <p class="text-xs sm:text-sm text-slate-500 font-medium mb-8 max-w-md">We've received your order. Please complete the payment to start shipping.</p>

        <div class="w-full bg-slate-50 rounded-2xl sm:rounded-3xl p-4 sm:p-6 mb-8 text-left border border-slate-100">
            <div class="flex justify-between items-center mb-4 pb-3 border-b border-slate-200/60 gap-3">
                <span class="text-[10px] sm:text-xs font-black uppercase text-slate-400 tracking-wider shrink-0">Order ID</span>
                <span class="text-xs sm:text-sm font-black text-slate-900 font-mono truncate">{{ $transaction->code }}</span>
            </div>
            
            <div class="space-y-3.5 mb-4">
                @foreach($transaction->details as $detail)
                <div class="flex items-start justify-between gap-3">
                    <div class="flex-1 min-w-0">
                        <p class="text-xs sm:text-sm font-bold text-slate-700 leading-snug line-clamp-2">{{ $detail->product->name }}</p>
                        <p class="text-[11px] font-semibold text-slate-400 mt-0.5">Qty: {{ $detail->quantity }}</p>
                    </div>
                    <span class="text-xs sm:text-sm font-black text-slate-900 shrink-0 whitespace-nowrap pt-0.5">Rp {{ number_format($detail->price * $detail->quantity, 0, ',', '.') }}</span>
                </div>
                @endforeach
            </div>

            <div class="h-px bg-slate-200 mb-4"></div>

            <div class="flex justify-between items-center mb-3 gap-3">
                <span class="text-xs font-bold text-slate-500 shrink-0">Shipping ({{ strtoupper($transaction->shipping_courier) }})</span>
                <span class="text-xs sm:text-sm font-bold text-slate-900 shrink-0 whitespace-nowrap">Rp {{ number_format($transaction->shipping_price, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between items-center gap-3 pt-2 border-t border-slate-200/60">
                <span class="text-xs sm:text-sm font-black uppercase tracking-wider text-[#006d5b] shrink-0 whitespace-nowrap">Total Amount</span>
                <span class="text-lg sm:text-xl font-black text-[#006d5b] shrink-0 whitespace-nowrap">Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="flex flex-col gap-4 w-full">
            @if($transaction->status === 'pending' && $transaction->payment_url)
            <a href="{{ $transaction->payment_url }}" class="w-full min-h-[64px] sm:min-h-[72px] py-4 bg-[#006d5b] text-white rounded-2xl flex items-center justify-center gap-3 font-black text-base sm:text-lg uppercase tracking-wider hover:bg-[#005647] active:scale-[0.99] transition shadow-xl shadow-[#006d5b]/25">
                <i class="ti ti-credit-card text-2xl"></i>
                Pay Securely Now
            </a>
            @endif
            
            <div class="flex flex-col sm:flex-row gap-3.5 sm:gap-4 w-full">
                <a href="/" class="flex-1 min-h-[60px] sm:min-h-[64px] py-3.5 px-4 bg-white border-2 border-slate-200 rounded-2xl flex items-center justify-center gap-2.5 font-black text-sm uppercase tracking-wider text-slate-700 hover:bg-slate-50 active:scale-[0.99] transition shadow-sm">
                    <i class="ti ti-shopping-cart text-xl text-slate-500"></i>
                    <span>Continue Shopping</span>
                </a>
                <a href="{{ route('account') }}" class="flex-1 min-h-[60px] sm:min-h-[64px] py-3.5 px-4 bg-slate-900 text-white rounded-2xl flex items-center justify-center gap-2.5 font-black text-sm uppercase tracking-wider hover:bg-slate-800 active:scale-[0.99] transition shadow-xl shadow-slate-900/20">
                    <i class="ti ti-package text-xl text-slate-300"></i>
                    <span>View My Orders</span>
                </a>
            </div>
        </div>
    </div>
</body>
</html>
