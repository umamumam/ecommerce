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
        .success-card { background: white; border-radius: 40px; box-shadow: 0 40px 100px -20px rgba(0,0,0,0.05); }
    </style>
</head>

<body class="antialiased flex items-center justify-center min-h-screen p-4">
    <div class="success-card max-w-2xl w-full p-10 lg:p-16 flex flex-col items-center text-center">
        <div class="w-24 h-24 bg-emerald-50 text-emerald-500 rounded-full flex items-center justify-center mb-8 animate-bounce">
            <i class="ti ti-check text-5xl"></i>
        </div>
        
        <h1 class="text-3xl lg:text-4xl font-black text-slate-900 uppercase tracking-tighter mb-4">Yeay! Order Placed</h1>
        <p class="text-slate-500 font-medium mb-10">We've received your order. Please complete the payment to start shipping.</p>

        <div class="w-full bg-slate-50 rounded-3xl p-8 mb-10 text-left border border-slate-100">
            <div class="flex justify-between items-center mb-6">
                <span class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Order ID</span>
                <span class="text-sm font-black text-slate-900">{{ $transaction->code }}</span>
            </div>
            
            <div class="space-y-4 mb-6">
                @foreach($transaction->details as $detail)
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-600">{{ $detail->product->name }} x {{ $detail->quantity }}</span>
                    <span class="text-xs font-black text-slate-900">Rp {{ number_format($detail->price * $detail->quantity, 0, ',', '.') }}</span>
                </div>
                @endforeach
            </div>

            <div class="h-px bg-slate-200 mb-6"></div>

            <div class="flex justify-between items-center mb-2">
                <span class="text-xs font-bold text-slate-500">Shipping ({{ strtoupper($transaction->shipping_courier) }})</span>
                <span class="text-xs font-bold text-slate-900">Rp {{ number_format($transaction->shipping_price, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-sm font-black uppercase tracking-widest text-[#006d5b]">Total Amount</span>
                <span class="text-xl font-black text-[#006d5b]">Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="flex flex-col gap-4 w-full">
            @if($transaction->status === 'pending' && $transaction->payment_url)
            <a href="{{ $transaction->payment_url }}" class="w-full h-16 bg-[#006d5b] text-white rounded-2xl flex items-center justify-center gap-3 font-black text-sm uppercase tracking-widest hover:bg-[#005647] transition shadow-xl shadow-[#006d5b]/20">
                <i class="ti ti-credit-card text-xl"></i>
                Pay Securely Now
            </a>
            @endif
            
            <div class="flex gap-4 w-full">
                <a href="/" class="flex-1 h-14 bg-white border border-slate-200 rounded-2xl flex items-center justify-center font-black text-[10px] uppercase tracking-widest hover:bg-slate-50 transition">
                    Continue Shopping
                </a>
                <a href="{{ route('account') }}" class="flex-1 h-14 bg-slate-900 text-white rounded-2xl flex items-center justify-center font-black text-[10px] uppercase tracking-widest hover:bg-slate-800 transition shadow-xl shadow-slate-900/20">
                    View My Orders
                </a>
            </div>
        </div>
    </div>
</body>
</html>
