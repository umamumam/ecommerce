<x-app-layout>
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4 uppercase tracking-tighter text-slate-800">
            <span class="text-muted fw-light">Integrasi /</span> Kurir Biteship & Payment
        </h4>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8" x-data="originManager()">
            <!-- Left Column: API Config -->
            <div class="lg:col-span-1">
                <form action="{{ route('admin.integration.updateKeys') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="card glass-card p-8 shadow-2xl border-0 h-fit rounded-[2.5rem]">
                        <div class="flex items-center gap-4 mb-8">
                            <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center">
                                <i class="ti ti-settings-automation text-2xl"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-black uppercase tracking-widest text-slate-900 leading-none mb-1">Konfigurasi API</h3>
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Global Settings</p>
                            </div>
                        </div>

                        <div class="space-y-8">
                            <!-- Biteship Key -->
                            <div>
                                <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest mb-3 block">Biteship API Key (Live/Test)</label>
                                <input type="text" name="biteship_api_key" value="{{ $accountInfo['biteship_api_key'] }}" 
                                    class="w-full bg-slate-50/80 p-4 rounded-2xl border border-slate-100 font-mono text-[10px] text-slate-700 focus:bg-white focus:border-emerald-500 transition-all outline-none">
                            </div>

                            <!-- Webhook -->
                            <div>
                                <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest mb-3 block">Biteship Webhook URL</label>
                                <input type="text" name="biteship_webhook" value="{{ $accountInfo['biteship_webhook'] }}" 
                                    class="w-full bg-indigo-50/50 p-4 rounded-2xl border border-indigo-100 font-mono text-[10px] text-indigo-600 focus:bg-white focus:border-indigo-500 transition-all outline-none">
                            </div>

                            <!-- Xendit Key -->
                            <div>
                                <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest mb-3 block">Xendit Secret Key</label>
                                <input type="password" name="xendit_secret_key" value="{{ $accountInfo['xendit_secret_key'] }}" 
                                    class="w-full bg-slate-50/80 p-4 rounded-2xl border border-slate-100 font-mono text-[10px] text-slate-700 focus:bg-white focus:border-emerald-500 transition-all outline-none">
                            </div>

                            <!-- Xendit Webhook Token -->
                            <div>
                                <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest mb-3 block">Xendit Webhook Verification Token</label>
                                <input type="password" name="xendit_webhook_token" value="{{ $accountInfo['xendit_webhook_token'] ?? '' }}" 
                                    class="w-full bg-slate-50/80 p-4 rounded-2xl border border-slate-100 font-mono text-[10px] text-slate-700 focus:bg-white focus:border-emerald-500 transition-all outline-none">
                                <p class="text-[9px] text-slate-400 mt-2">Gunakan token ini untuk verifikasi pengiriman data (callback) dari Xendit.</p>
                            </div>
                        </div>

                        <div class="mt-10 pt-8 border-t border-slate-100">
                            <button type="submit" class="w-full py-4 bg-slate-900 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-[#006d5b] transition-all shadow-xl">
                                Simpan Perubahan API
                            </button>
                        </div>

                        <div class="mt-6 flex items-center justify-between">
                            <div class="flex flex-col">
                                <span class="text-[10px] font-black uppercase text-slate-400 mb-1">Status</span>
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                                    <span class="text-[10px] font-black text-slate-900 uppercase italic">Connected</span>
                                </div>
                            </div>
                            <span class="px-3 py-1 bg-emerald-100 text-emerald-600 text-[8px] font-black rounded-lg uppercase">
                                {{ Str::contains($accountInfo['biteship_api_key'], 'live') ? 'Mode Live' : 'Mode Testing' }}
                            </span>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Right Column: Origin Configuration -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Search Section -->
                <div class="card glass-card shadow-2xl border-0 rounded-[2.5rem] overflow-hidden">
                    <div class="p-8 pb-4">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-10 h-10 bg-[#006d5b]/10 text-[#006d5b] rounded-xl flex items-center justify-center">
                                <i class="ti ti-search text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-black uppercase tracking-widest text-slate-800">Set Lokasi Penjemputan</h3>
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Cari berdasarkan Kecamatan</p>
                            </div>
                        </div>

                        <div class="relative mb-6">
                            <input type="text" x-model="areaSearch" @input.debounce.500ms="searchArea()" 
                                placeholder="Ketik nama kecamatan atau kota..." 
                                class="w-full px-6 py-4 rounded-2xl border border-slate-100 bg-slate-50 focus:bg-white focus:border-[#006d5b] focus:ring-0 transition font-bold text-sm">
                            
                            <div x-show="areaResults.length > 0" x-cloak @click.away="areaResults = []"
                                class="absolute left-0 right-0 mt-3 bg-white rounded-3xl shadow-2xl border border-slate-100 z-50 max-h-60 overflow-y-auto no-scrollbar py-2">
                                <template x-for="area in areaResults" :key="area.id">
                                    <div @click="selectArea(area)" 
                                        class="px-6 py-4 hover:bg-slate-50 cursor-pointer border-b border-slate-50 last:border-0 flex flex-col group">
                                        <span class="text-xs font-black text-slate-800 group-hover:text-[#006d5b]" x-text="area.name"></span>
                                        <span class="text-[9px] text-slate-400 uppercase mt-1" x-text="area.formatted_address"></span>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <div x-show="selectedArea" x-cloak class="p-5 bg-[#006d5b]/5 border-2 border-dashed border-[#006d5b]/20 rounded-2xl flex items-center justify-between mb-6">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-[#006d5b] text-white rounded-xl flex items-center justify-center shadow-lg">
                                    <i class="ti ti-check"></i>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-xs font-black text-[#006d5b] uppercase tracking-widest" x-text="selectedArea.name"></span>
                                    <span class="text-[9px] font-bold text-slate-400" x-text="selectedArea.formatted_address"></span>
                                </div>
                            </div>
                            <form action="{{ route('admin.integration.updateOrigin') }}" method="POST">
                                @csrf
                                <input type="hidden" name="origin_id" :value="selectedArea.id">
                                <input type="hidden" name="origin_label" :value="selectedArea.name">
                                <button type="submit" class="btn btn-primary btn-sm rounded-xl px-6 py-2 text-[10px] font-black uppercase">Simpan Sebagai Origin</button>
                            </form>
                        </div>
                        
                        @if($currentOriginLabel)
                        <div class="flex items-center gap-3 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                            <span class="text-[9px] font-black uppercase text-slate-400 tracking-widest">Origin Aktif Sistem:</span>
                            <span class="text-[10px] font-black text-emerald-600 uppercase italic">{{ $currentOriginLabel }}</span>
                            <span class="text-[9px] font-mono text-slate-300">({{ $currentOriginId }})</span>
                        </div>
                        @else
                        <div class="flex items-center gap-3 p-4 bg-amber-50 rounded-2xl border border-amber-100">
                            <i class="ti ti-alert-circle text-amber-500 text-sm"></i>
                            <span class="text-[10px] font-bold text-amber-600">Origin belum diset di Database. Menggunakan fallback .env.</span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Saved Locations List -->
                <div class="card glass-card shadow-2xl border-0 rounded-[2.5rem] overflow-hidden">
                    <div class="p-8 border-b border-slate-50 flex items-center justify-between bg-white/50">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center">
                                <i class="ti ti-building-warehouse text-xl"></i>
                            </div>
                            <h3 class="text-sm font-black uppercase tracking-widest text-slate-800">Alamat Tersimpan (Dashboard Biteship)</h3>
                        </div>
                    </div>

                    <div class="card-body p-8 overflow-y-auto max-h-[500px] no-scrollbar">
                        <div class="grid grid-cols-1 gap-6">
                            @forelse($locations as $loc)
                            <div class="group relative overflow-hidden bg-white border border-slate-100 p-6 rounded-[2rem] transition-all hover:border-[#006d5b] hover:shadow-2xl">
                                <div class="relative z-10 flex flex-wrap md:flex-nowrap items-start justify-between gap-6">
                                    <div class="flex gap-5">
                                        <div class="w-14 h-14 bg-slate-50 text-slate-300 rounded-2xl flex items-center justify-center group-hover:bg-[#006d5b] group-hover:text-white transition-all duration-500">
                                            <i class="ti ti-map-pin text-2xl"></i>
                                        </div>
                                        <div class="flex flex-col pt-1">
                                            <div class="flex items-center gap-3 mb-1">
                                                <h4 class="text-[13px] font-black uppercase tracking-tight text-slate-900 leading-none">{{ $loc['name'] }}</h4>
                                                @if($currentOriginId == $loc['id'])
                                                <span class="px-2 py-0.5 bg-emerald-100 text-emerald-600 text-[7px] font-black rounded-md uppercase">Default</span>
                                                @endif
                                            </div>
                                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-4 italic">PIC: {{ $loc['contact_name'] }} • {{ $loc['contact_phone'] }}</span>
                                            <p class="text-[11px] text-slate-500 max-w-lg leading-relaxed line-clamp-2">
                                                {{ $loc['address'] }}
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex flex-col items-end gap-3 shrink-0">
                                        <div class="px-2 py-1 bg-slate-50 text-slate-400 rounded-lg font-mono text-[8px] font-black uppercase tracking-tighter">
                                            ID: {{ $loc['id'] }}
                                        </div>
                                        @if($currentOriginId != $loc['id'])
                                        <form action="{{ route('admin.integration.updateOrigin') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="origin_id" value="{{ $loc['id'] }}">
                                            <input type="hidden" name="origin_label" value="{{ $loc['name'] }}">
                                            <button type="submit" class="text-[9px] font-black uppercase text-[#006d5b] hover:underline underline-offset-4 tracking-widest">
                                                Set Default
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="py-24 text-center">
                                <p class="text-[10px] font-bold text-slate-400 italic">Tidak ada lokasi tersimpan di dashboard Biteship.</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function originManager() {
            return {
                areaSearch: '',
                areaResults: [],
                selectedArea: null,

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
                }
            }
        }
    </script>

    <style>
        .glass-card { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(20px); }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</x-app-layout>