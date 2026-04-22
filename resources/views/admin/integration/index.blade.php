<x-app-layout>
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4 uppercase tracking-tighter text-slate-800">
            <span class="text-muted fw-light">Integrasi /</span> Kurir Biteship
        </h4>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Account Config -->
            <div class="lg:col-span-1">
                <div class="card glass-card p-8 shadow-2xl border-0 h-fit rounded-[2.5rem]">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center">
                            <i class="ti ti-settings-automation text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-black uppercase tracking-widest text-slate-900 leading-none mb-1">Akun</h3>
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Koneksi Biteship</p>
                        </div>
                    </div>

                    <div class="space-y-8">
                        <div>
                            <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest mb-3 block">Live API Key</label>
                            <div class="bg-slate-50/80 p-4 rounded-2xl border border-slate-100 font-mono text-[10px] text-slate-400 break-all relative group overflow-hidden">
                                <span class="relative z-10">{{ $accountInfo['api_key_masked'] }}</span>
                                <button onclick="navigator.clipboard.writeText('{{ env('BITESHIP_API_KEY') }}'); Swal.fire({title:'Key Copied!', icon:'success', timer:1500, showConfirmButton:false})" 
                                    class="absolute right-3 top-1/2 -translate-y-1/2 w-8 h-8 rounded-lg bg-white shadow-sm flex items-center justify-center text-slate-400 hover:text-emerald-500 transition-all opacity-0 group-hover:opacity-100">
                                    <i class="ti ti-copy text-xs"></i>
                                </button>
                            </div>
                        </div>

                        <div>
                            <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest mb-3 block">Webhook URL</label>
                            <div class="bg-indigo-50/50 p-5 rounded-3xl border border-indigo-100 relative group transition-all">
                                <span class="text-[10px] font-black text-indigo-600 block mb-2 font-mono uppercase tracking-tighter">BITESHIP_CALLBACK</span>
                                <div class="flex items-center justify-between gap-4">
                                    <span class="text-[11px] font-bold text-slate-600 truncate">{{ $accountInfo['webhook_url'] }}</span>
                                    <button onclick="navigator.clipboard.writeText('{{ $accountInfo['webhook_url'] }}'); Swal.fire({title:'URL Copied!', icon:'success', timer:1500, showConfirmButton:false})" 
                                        class="w-8 h-8 rounded-xl bg-white text-indigo-600 shadow-sm border border-indigo-100 flex items-center justify-center hover:bg-indigo-600 hover:text-white transition-all">
                                        <i class="ti ti-copy text-xs"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-10 pt-8 border-t border-slate-100 flex items-center justify-between">
                        <div class="flex flex-col">
                            <span class="text-[10px] font-black uppercase text-slate-400 mb-1">Status</span>
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                                <span class="text-[10px] font-black text-slate-900 uppercase italic">Connected</span>
                            </div>
                        </div>
                        <span class="px-3 py-1 bg-amber-100 text-amber-600 text-[8px] font-black rounded-lg uppercase">Production</span>
                    </div>
                </div>
            </div>

            <!-- Right Column: Origin Locations -->
            <div class="lg:col-span-2">
                <div class="card glass-card shadow-2xl border-0 rounded-[2.5rem] overflow-hidden min-h-full flex flex-col">
                    <div class="p-8 border-b border-slate-50 flex items-center justify-between bg-white/50">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center">
                                <i class="ti ti-map-pin text-xl"></i>
                            </div>
                            <h3 class="text-sm font-black uppercase tracking-widest text-slate-800">Alamat Asal (Origin)</h3>
                        </div>
                        <button class="btn btn-dark h-10 px-6 rounded-xl text-[9px] font-black uppercase tracking-widest shadow-lg">
                            + Tambah Alamat
                        </button>
                    </div>

                    <div class="card-body p-8 flex-grow-1">
                        <div class="grid grid-cols-1 gap-6">
                            @forelse($locations as $loc)
                            <div class="group relative overflow-hidden bg-white border border-slate-100 p-6 rounded-[2rem] transition-all hover:border-emerald-500 hover:shadow-2xl hover:shadow-emerald-500/5">
                                <div class="relative z-10 flex flex-wrap lg:flex-nowrap items-start justify-between gap-6">
                                    <div class="flex gap-5">
                                        <div class="w-14 h-14 bg-slate-50 text-slate-300 rounded-2xl flex items-center justify-center group-hover:bg-emerald-500 group-hover:text-white transition-all duration-500">
                                            <i class="ti ti-building-warehouse text-2xl"></i>
                                        </div>
                                        <div class="flex flex-col pt-1">
                                            <div class="flex items-center gap-3 mb-1">
                                                <h4 class="text-[13px] font-black uppercase tracking-tight text-slate-900 leading-none">{{ $loc['name'] }}</h4>
                                                @if(env('BITESHIP_ORIGIN_ID') == $loc['id'])
                                                <span class="px-2 py-0.5 bg-emerald-100 text-emerald-600 text-[7px] font-black rounded-md uppercase">Default</span>
                                                @endif
                                            </div>
                                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-4 italic">PIC: {{ $loc['contact_name'] }} • {{ $loc['contact_phone'] }}</span>
                                            <p class="text-[11px] text-slate-500 max-w-lg leading-relaxed line-clamp-2">
                                                {{ $loc['address'] }}
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex flex-col items-end gap-3">
                                        <div class="px-2 py-1 bg-slate-50 text-slate-400 rounded-lg font-mono text-[8px] font-black uppercase tracking-tighter">
                                            ID: {{ $loc['id'] }}
                                        </div>
                                        @if(env('BITESHIP_ORIGIN_ID') != $loc['id'])
                                        <button class="text-[9px] font-black uppercase text-indigo-600 hover:text-indigo-400 transition-colors tracking-widest underline underline-offset-4">
                                            Set Default
                                        </button>
                                        @endif
                                    </div>
                                </div>
                                <div class="absolute right-0 bottom-0 opacity-0 group-hover:opacity-[0.03] transition-opacity duration-500 pointer-events-none p-4">
                                    <i class="ti ti-truck text-[80px] rotate-12"></i>
                                </div>
                            </div>
                            @empty
                            <div class="py-24 text-center h-full flex flex-col items-center justify-center">
                                <img src="{{ asset('assets/img/illustrations/page-misc-under-maintenance.png') }}" class="h-40 mb-8 grayscale opacity-20" alt="empty">
                                <h4 class="text-xs font-black uppercase text-slate-300 tracking-[0.2em]">Belum Ada Lokasi Origin</h4>
                                <p class="text-[10px] font-bold text-slate-400 mt-2">Daftarkan lokasi gudang atau toko Anda untuk mulai melakukan pengiriman.</p>
                            </div>
                            @endforelse
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