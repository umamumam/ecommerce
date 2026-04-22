@extends('layouts.app')

@section('content')
<style>
    .tracking-timeline::before {
        content: '';
        position: absolute;
        left: 0.75rem;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e2e8f0;
    }
    .timeline-item.active .bullet { background: #006d5b; box-shadow: 0 0 0 4px rgba(0, 109, 91, 0.2); }
    .timeline-item.active .line { background: #006d5b; }
</style>

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-black uppercase tracking-tight text-slate-900">Transaction Detail</h1>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Invoice: {{ $transaction->code }}</p>
            </div>
            <div class="flex flex-col items-end">
                <span class="text-[10px] font-black uppercase text-slate-400 mb-2">Order Status</span>
                @php
                    $statusColors = [
                        'pending' => 'bg-amber-100 text-amber-600',
                        'paid' => 'bg-blue-100 text-blue-600',
                        'process' => 'bg-indigo-100 text-indigo-600',
                        'shipping' => 'bg-purple-100 text-purple-600',
                        'completed' => 'bg-emerald-100 text-emerald-600',
                        'cancelled' => 'bg-rose-100 text-rose-600',
                    ];
                    $badgeClass = $statusColors[$transaction->status] ?? 'bg-slate-100 text-slate-600';
                @endphp
                <span class="{{ $badgeClass }} text-[10px] font-black px-4 py-1.5 rounded-full uppercase tracking-widest">
                    {{ $transaction->status }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left: Items & Tracking -->
            <div class="lg:col-span-2 flex flex-col gap-8">
                <!-- Items Table -->
                <div class="bg-white rounded-[32px] shadow-sm border border-slate-100 overflow-hidden">
                    <div class="p-6 lg:p-8 bg-slate-50/50 border-b border-slate-100">
                        <h3 class="text-sm font-black uppercase tracking-widest text-slate-900">Ordered Items</h3>
                    </div>
                    <div class="p-6 lg:p-8 flex flex-col gap-6">
                        @foreach($transaction->details as $detail)
                        <div class="flex items-center gap-4">
                            @php
                                $img = is_array($detail->product->images) && count($detail->product->images) > 0 ? $detail->product->images[0] : null;
                                $src = $img ? (Str::startsWith($img, 'http') ? $img : asset('storage/'.$img)) : asset('assets/img/elements/1.jpg');
                            @endphp
                            <div class="w-16 h-16 rounded-2xl overflow-hidden shadow-sm shrink-0">
                                <img src="{{ $src }}" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-1">
                                <h4 class="text-xs font-black uppercase text-slate-900">{{ $detail->product->name }}</h4>
                                <p class="text-[10px] text-slate-400 font-bold mt-1">
                                    {{ $detail->quantity }} Unit x Rp {{ number_format($detail->price, 0, ',', '.') }}
                                </p>
                            </div>
                            <div class="text-right">
                                <span class="text-xs font-black text-slate-900">Rp {{ number_format($detail->price * $detail->quantity, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Shipping Status (Visual Timeline) -->
                <div class="bg-white rounded-[32px] shadow-sm border border-slate-100 p-8 lg:p-10">
                    <h3 class="text-sm font-black uppercase tracking-widest text-slate-900 mb-8">Shipping Journey</h3>
                    
                    @if($transaction->shipping_waybill)
                        <div class="relative tracking-timeline pl-10 space-y-8">
                            <div class="timeline-item flex gap-4 {{ in_array($transaction->status, ['completed']) ? 'active' : '' }}">
                                <div class="bullet absolute left-[0.45rem] w-3 h-3 rounded-full bg-slate-200 z-10"></div>
                                <div class="flex flex-col">
                                    <span class="text-xs font-black uppercase text-slate-900">Package Delivered</span>
                                    <p class="text-[10px] text-slate-400 font-medium">Recipient has received the package.</p>
                                </div>
                            </div>
                            <div class="timeline-item flex gap-4 {{ in_array($transaction->status, ['shipping', 'completed']) ? 'active' : '' }}">
                                <div class="bullet absolute left-[0.45rem] w-3 h-3 rounded-full bg-slate-200 z-10"></div>
                                <div class="flex flex-col">
                                    <span class="text-xs font-black uppercase text-slate-900">In Transit</span>
                                    <p class="text-[10px] text-slate-400 font-medium">Package is on its way to destination via {{ strtoupper($transaction->shipping_courier) }}</p>
                                </div>
                            </div>
                            <div class="timeline-item flex gap-4 active">
                                <div class="bullet absolute left-[0.45rem] w-3 h-3 rounded-full bg-slate-200 z-10"></div>
                                <div class="flex flex-col">
                                    <span class="text-xs font-black uppercase text-slate-900">Order Processed</span>
                                    <p class="text-[10px] text-slate-400 font-medium">{{ $transaction->created_at->format('d M Y, H:i') }} • Arrived at warehouse</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-10 p-5 bg-indigo-50/50 rounded-2xl border border-indigo-100 flex items-center justify-between">
                            <div class="flex flex-col">
                                <span class="text-[9px] font-black uppercase text-indigo-400 tracking-widest">Tracking Number</span>
                                <span class="text-xs font-black text-indigo-900">{{ $transaction->shipping_waybill }}</span>
                            </div>
                            <button class="px-4 py-2 bg-indigo-500 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-indigo-600 transition">Copy</button>
                        </div>
                    @else
                        <div class="text-center py-10">
                            <div class="w-16 h-16 bg-slate-50 text-slate-200 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="ti ti-hourglass-empty text-3xl"></i>
                            </div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Awaiting Shipping Info</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right: Shipping Info & Payment -->
            <div class="lg:col-span-1 flex flex-col gap-6">
                <!-- Shipping Address Card -->
                <div class="bg-white rounded-[32px] shadow-sm border border-slate-100 p-8 flex flex-col">
                    <h3 class="text-xs font-black uppercase tracking-widest text-slate-400 mb-6">Ship To</h3>
                    <div class="flex flex-col gap-1">
                        <span class="text-sm font-black text-slate-900">{{ $transaction->shipping_name }}</span>
                        <span class="text-[10px] font-bold text-slate-500">{{ $transaction->shipping_phone }}</span>
                        <p class="text-xs text-slate-600 mt-4 leading-relaxed font-medium">
                            {{ $transaction->shipping_address }}
                        </p>
                    </div>
                </div>

                <!-- Payment Summary -->
                <div class="bg-slate-900 rounded-[32px] shadow-xl p-8 flex flex-col text-white">
                    <h3 class="text-xs font-black uppercase tracking-widest text-white/40 mb-6">Payment Summary</h3>
                    <div class="flex flex-col gap-4 mb-8">
                        <div class="flex justify-between items-center text-xs font-medium text-white/60">
                            <span>Total Items</span>
                            <span class="text-white">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center text-xs font-medium text-white/60">
                            <span>Shipping</span>
                            <span class="text-white">Rp {{ number_format($transaction->shipping_price, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    <div class="h-px bg-white/10 mb-6"></div>
                    <div class="flex justify-between items-center">
                        <span class="text-[10px] font-black uppercase tracking-widest text-white/40">Grand Total</span>
                        <span class="text-xl font-black text-white">Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Quick Action -->
                @if($transaction->status === 'pending')
                <button class="w-full h-14 bg-emerald-500 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-emerald-600 transition shadow-xl shadow-emerald-500/20">
                    Pay Now
                </button>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
