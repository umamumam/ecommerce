<x-app-layout>
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4 uppercase tracking-tighter text-slate-800">
            <span class="text-muted fw-light">Integrasi /</span> API Xendit
        </h4>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Configuration -->
            <div class="lg:col-span-1">
                <div class="card glass-card p-8 shadow-2xl border-0 h-fit rounded-[2.5rem]">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center">
                            <i class="ti ti-shield-lock text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-black uppercase tracking-widest text-slate-900 leading-none mb-1">Konfigurasi</h3>
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Keamanan & Webhook</p>
                        </div>
                    </div>

                    <div class="space-y-8">
                        <div>
                            <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest mb-3 block">Secret API Key</label>
                            <div class="bg-slate-50/80 p-4 rounded-2xl border border-slate-100 font-mono text-[10px] text-slate-400 break-all relative">
                                {{ $config['secret_key'] }}
                                <div class="absolute right-4 top-4">
                                    <i class="ti ti-lock text-slate-200"></i>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest mb-3 block">Webhook Callback</label>
                            <div class="bg-indigo-50/50 p-5 rounded-3xl border border-indigo-100 relative group transition-all">
                                <span class="text-[10px] font-black text-indigo-600 block mb-2 font-mono">XENDIT_WEBHOOK_URL</span>
                                <div class="flex items-center justify-between gap-4">
                                    <span class="text-[11px] font-bold text-slate-600 truncate">{{ $config['webhook_url'] }}</span>
                                    <button onclick="navigator.clipboard.writeText('{{ $config['webhook_url'] }}'); Swal.fire({title:'URL Copied!', icon:'success', customClass:{container:'my-swal'}, timer:1500, showConfirmButton:false})" 
                                        class="w-8 h-8 rounded-xl bg-white text-indigo-600 shadow-sm border border-indigo-100 flex items-center justify-center hover:bg-indigo-600 hover:text-white transition-all">
                                        <i class="ti ti-copy text-xs"></i>
                                    </button>
                                </div>
                            </div>
                            <span class="text-[9px] text-slate-400 mt-3 block italic leading-relaxed px-2">
                                * Daftarkan URL ini di Dashboard Xendit menu <strong>Webhooks</strong> bagian <strong>Invoice Paid</strong>.
                            </span>
                        </div>
                    </div>

                    <div class="mt-10 pt-8 border-t border-slate-100 flex items-center justify-between">
                        <div class="flex flex-col">
                            <span class="text-[10px] font-black uppercase text-slate-400 mb-1">Environment</span>
                            <span class="text-xs font-black text-slate-900 uppercase italic">Development</span>
                        </div>
                        <img src="https://upload.wikimedia.org/wikipedia/commons/4/4c/Xendit_Logo_Transparent.png" class="h-4 object-contain opacity-40 grayscale" alt="Xendit">
                    </div>
                </div>
            </div>

            <!-- Right Column: Info & Logs -->
            <div class="lg:col-span-2">
                <div class="flex flex-col gap-8 h-full">
                    <!-- Balance Summary Card -->
                    <div class="card bg-dark shadow-2xl border-0 rounded-[2.5rem] overflow-hidden relative min-h-[160px] flex items-center">
                        <div class="card-body p-10 relative z-10 flex w-full items-center justify-between">
                            <div class="flex flex-col">
                                <span class="text-[10px] font-black uppercase tracking-[0.2em] text-white/40 mb-3 block">Saldo Terhitung</span>
                                <h1 class="text-4xl lg:text-5xl font-black italic uppercase italic tracking-tighter text-white">
                                    Rp {{ number_format($balance, 0, ',', '.') }}
                                </h1>
                                <div class="flex items-center gap-2 mt-4">
                                    <div class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></div>
                                    <span class="text-[10px] font-black uppercase tracking-widest text-emerald-400">Live Status Active</span>
                                </div>
                            </div>
                            <div class="d-none d-md-block text-right">
                                <i class="ti ti-wallet text-7xl text-white/10"></i>
                            </div>
                        </div>
                        <div class="absolute right-0 top-0 h-full w-1/2 bg-gradient-to-l from-indigo-500/20 to-transparent pointer-events-none"></div>
                    </div>

                    <!-- Logs Table -->
                    <div class="card glass-card shadow-2xl border-0 rounded-[2.5rem] overflow-hidden flex-grow-1 flex flex-col">
                        <div class="p-8 border-b border-slate-50 flex items-center justify-between bg-white/50">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center">
                                    <i class="ti ti-activity text-xl"></i>
                                </div>
                                <h3 class="text-sm font-black uppercase tracking-widest text-slate-800">Riwayat Webhook</h3>
                            </div>
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">10 Event Terakhir</span>
                        </div>

                        <div class="card-body p-0 flex-grow-1">
                            @if(count($logs) > 0)
                            <div class="table-responsive no-scrollbar">
                                <table class="table table-hover align-middle">
                                    <thead>
                                        <tr class="text-[10px] font-black uppercase text-slate-400 tracking-widest bg-slate-50/30">
                                            <th class="ps-8 py-4 border-0">Waktu</th>
                                            <th class="py-4 border-0">Event</th>
                                            <th class="py-4 border-0">Invoice</th>
                                            <th class="pe-8 py-4 border-0 text-end">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($logs as $log)
                                        <tr class="transition-all hover:bg-slate-50/50">
                                            <td class="ps-8 py-5 border-0">
                                                <div class="flex flex-col">
                                                    <span class="text-[11px] font-black text-slate-900">{{ $log->created_at->format('d M Y') }}</span>
                                                    <span class="text-[9px] font-bold text-slate-400 uppercase">{{ $log->created_at->format('H:i') }}</span>
                                                </div>
                                            </td>
                                            <td class="border-0">
                                                <span class="px-2 py-1 rounded-lg bg-indigo-50 text-indigo-600 text-[9px] font-black uppercase tracking-tighter">
                                                    {{ $log->event }}
                                                </span>
                                            </td>
                                            <td class="border-0">
                                                <span class="text-[11px] font-black text-slate-500 font-mono tracking-tighter">{{ $log->payload['external_id'] ?? '-' }}</span>
                                            </td>
                                            <td class="pe-8 border-0 text-end">
                                                @if($log->status == 'processed')
                                                <span class="inline-flex items-center gap-1 text-emerald-500 text-[10px] font-black uppercase">
                                                    <i class="ti ti-discount-check-filled text-xs"></i> Success
                                                </span>
                                                @else
                                                <span class="inline-flex items-center gap-1 text-amber-500 text-[10px] font-black uppercase">
                                                    <i class="ti ti-info-circle-filled text-xs"></i> {{ strtoupper($log->status) }}
                                                </span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <div class="py-20 flex flex-col items-center text-center px-10 h-full justify-center">
                                <img src="{{ asset('assets/img/illustrations/page-misc-error.png') }}" class="h-32 mb-6 grayscale opacity-20" alt="empty">
                                <h4 class="text-xs font-black uppercase text-slate-300 tracking-[0.2em]">Belum Ada Data Masuk</h4>
                                <p class="text-[10px] font-bold text-slate-400 mt-2 max-w-xs">Sistem akan otomatis mencatat setiap aktivitas pembayaran dari webhook Xendit di sini.</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .glass-card { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(20px); }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</x-app-layout>