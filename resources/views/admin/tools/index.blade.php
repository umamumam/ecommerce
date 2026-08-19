<x-app-layout>
    <div class="container-xxl flex-grow-1 container-p-y" x-data="shippingTools()">
        <h4 class="fw-bold py-3 mb-4 uppercase tracking-tighter">Logistics Suite</h4>

        <!-- Cek Ongkir - Biteship Style -->
        <div class="card glass-card p-0 shadow-2xl mb-10 overflow-hidden border-0">
            <div class="bg-[#006d5b] p-8 text-white">
                <h3 class="text-xl font-black uppercase tracking-widest mb-2">Cek Ongkir Semua Ekspedisi</h3>
                <p class="text-xs text-white/70 font-medium tracking-wide">Cek tarif pengiriman cepat, akurat, dan terintegrasi Biteship API</p>
            </div>
            
            <div class="p-8">
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 items-end bg-slate-50 p-6 rounded-[2rem] border border-slate-100">
                    <!-- Wilayah Asal -->
                    <div class="relative">
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest mb-3 block">Wilayah Asal</label>
                        <input type="text" x-model="originSearch" @input.debounce.500ms="searchOrigin()" 
                            placeholder="Cari kecamatan asal..." 
                            class="form-control h-12 rounded-xl border-slate-200 text-xs font-bold focus:border-[#006d5b] focus:ring-0">
                        
                        <div x-show="originResults.length > 0" class="absolute left-0 right-0 mt-2 bg-white rounded-xl shadow-2xl border border-slate-100 z-[60] max-h-48 overflow-y-auto no-scrollbar">
                            <template x-for="area in originResults" :key="area.id">
                                <div @click="selectOrigin(area)" class="px-4 py-2.5 hover:bg-slate-50 cursor-pointer border-b border-slate-50 last:border-0">
                                    <span class="text-[11px] font-black block text-slate-900" x-text="area.name"></span>
                                    <span class="text-[9px] text-slate-400" x-text="area.formatted_address"></span>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Wilayah Tujuan -->
                    <div class="relative">
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest mb-3 block">Wilayah Tujuan</label>
                        <input type="text" x-model="destSearch" @input.debounce.500ms="searchDest()" 
                            placeholder="Cari kecamatan tujuan..." 
                            class="form-control h-12 rounded-xl border-slate-200 text-xs font-bold focus:border-[#006d5b] focus:ring-0">
                        
                        <div x-show="destResults.length > 0" class="absolute left-0 right-0 mt-2 bg-white rounded-xl shadow-2xl border border-slate-100 z-[60] max-h-48 overflow-y-auto no-scrollbar">
                            <template x-for="area in destResults" :key="area.id">
                                <div @click="selectDest(area)" class="px-4 py-2.5 hover:bg-slate-50 cursor-pointer border-b border-slate-50 last:border-0">
                                    <span class="text-[11px] font-black block text-slate-900" x-text="area.name"></span>
                                    <span class="text-[9px] text-slate-400" x-text="area.formatted_address"></span>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Berat -->
                    <div>
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest mb-3 block">Berat (Gram)</label>
                        <input type="number" x-model="ratesForm.weight" class="form-control h-12 rounded-xl border-slate-200 text-xs font-bold focus:border-[#006d5b]">
                    </div>

                    <!-- Submit -->
                    <button @click="getRates()" class="btn btn-primary-teal h-12 rounded-xl font-black uppercase tracking-widest text-xs shadow-lg shadow-[#006d5b]/20" :disabled="loadingRates || !ratesForm.origin || !ratesForm.destination">
                        <span x-show="!loadingRates">Cek Ongkir</span>
                        <span x-show="loadingRates" class="spinner-border spinner-border-sm"></span>
                    </button>
                </div>

                <!-- Rates Display -->
                <div x-show="ratesResults.length > 0" class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 animate-fade-up">
                    <template x-for="rate in ratesResults" :key="rate.courier_code + rate.courier_service_code">
                        <div class="p-5 border-2 border-slate-100 rounded-3xl bg-white hover:border-[#006d5b] transition-all group shadow-sm">
                            <div class="flex items-center justify-between mb-4">
                                <div class="h-8 w-20 bg-white p-1 rounded-lg border border-slate-100 flex items-center justify-center">
                                    <img :src="rate.courier_logo || ('/assets/img/couriers/' + (rate.courier_code || 'default') + '.webp')" 
                                         class="h-full w-full object-contain" 
                                         :alt="rate.courier_name"
                                         onerror="this.src='/assets/img/couriers/default.svg'">
                                </div>
                                <span class="text-xs font-black text-[#006d5b]" x-text="rate.formatted_price || ('Rp ' + new Intl.NumberFormat('id-ID').format(rate.price))"></span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-[10px] font-black uppercase text-slate-900" x-text="rate.courier_name"></span>
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter" x-text="rate.courier_service_name"></span>
                                <span class="text-[8px] text-slate-400 mt-2" x-text="'Estimasi: ' + (rate.formatted_duration || rate.duration)"></span>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-1">
                <!-- Cek Resi -->
                <div class="card glass-card p-8 shadow-xl h-fit border-0">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center">
                            <i class="ti ti-truck text-2xl"></i>
                        </div>
                        <h3 class="text-sm font-black uppercase tracking-widest text-slate-900">Tracking Resi</h3>
                    </div>

                    <div class="space-y-6">
                        <div class="relative">
                            <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest mb-3 block">Nomor Resi / Waybill ID</label>
                            <div class="flex gap-2 bg-slate-50 p-2 rounded-2xl border border-slate-100">
                                <input type="text" x-model="trackForm.waybill" placeholder="e.g. JB002131..." class="form-control border-0 bg-transparent text-xs font-bold focus:ring-0">
                                <button @click="trackResi()" class="btn btn-dark rounded-xl px-4 h-10" :disabled="loadingTrack">
                                    <i class="ti ti-search" x-show="!loadingTrack"></i>
                                    <span x-show="loadingTrack" class="spinner-border spinner-border-sm w-4 h-4"></span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Result Tracking -->
                    <div x-show="trackResult" class="mt-8 border-t pt-8 animate-fade-up">
                        <div class="flex items-center justify-between mb-8">
                            <span class="status-badge bg-indigo-50 text-indigo-600 px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest" x-text="trackResult.status"></span>
                            <span class="text-[10px] font-black text-slate-900" x-text="trackResult.courier?.name"></span>
                        </div>
                        <!-- Clean Vertical Timeline -->
                        <div class="relative pl-2 max-h-[350px] overflow-y-auto pr-3 no-scrollbar mt-6">
                            <div class="absolute left-[19px] top-3 bottom-5 w-[2px] bg-slate-300"></div>

                            <div class="space-y-5">
                                <template x-for="(history, index) in (trackResult.history || [])" :key="index">
                                    <div class="relative flex items-start gap-3.5">
                                        <div class="w-5 h-5 rounded-full border-2 bg-white flex items-center justify-center shrink-0 z-10"
                                             :class="index === ((trackResult.history?.length || 1) - 1) ? 'border-[#006d5b]' : 'border-slate-400'">
                                            <div class="w-1.5 h-1.5 rounded-full"
                                                 :class="index === ((trackResult.history?.length || 1) - 1) ? 'bg-[#006d5b]' : 'bg-transparent'"></div>
                                        </div>
                                        <div class="flex flex-col pt-0.5 min-w-0">
                                            <span class="text-[10px] text-slate-400 font-medium leading-none mb-1" x-text="formatTimelineDate(history.updated_at)"></span>
                                            <p class="text-[11px] font-bold text-slate-800 leading-snug mb-0" x-text="history.note || history.status || '-'"></p>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="lg:col-span-2">
                <!-- Helper or Info card can go here -->
                <div class="bg-[#006d5b]/5 p-10 rounded-[2.5rem] border-2 border-dashed border-[#006d5b]/20 flex flex-col items-center text-center">
                    <img src="{{ asset('assets/img/illustrations/page-pricing-standard.png') }}" class="h-48 mb-8" alt="illustration">
                    <h4 class="text-xl font-black text-[#006d5b] uppercase tracking-tighter mb-4">Butuh Bantuan Logistik?</h4>
                    <p class="text-slate-500 text-xs font-medium max-w-sm leading-relaxed mb-6">
                        Layanan ini terhubung langsung dengan API Biteship untuk memberikan data paling akurat di Indonesia. Pastikan kecamatan yang Anda cari sudah muncul di dropdown agar data Area ID valid.
                    </p>
                    <a href="https://biteship.com" target="_blank" class="text-[10px] font-black uppercase tracking-widest text-[#006d5b] border-b-2 border-[#006d5b] pb-1">Buka Dashboard Utama Biteship</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function shippingTools() {
            return {
                loadingRates: false,
                loadingTrack: false,
                ratesForm: { origin: '{{ env('BITESHIP_ORIGIN_ID', 'IDNP3CL1044') }}', destination: '', weight: 1000 },
                
                originSearch: '{{ env('BITESHIP_ORIGIN_NAME', 'Jakarta Selatan') }}',
                originResults: [],
                
                destSearch: '',
                destResults: [],

                ratesResults: [],
                trackForm: { waybill: '' },
                trackResult: null,

                async searchOrigin() {
                    if (this.originSearch.length < 3) return;
                    try {
                        const res = await fetch(`/shipping/areas?q=${this.originSearch}`);
                        const data = await res.json();
                        this.originResults = data.areas || [];
                    } catch (e) { console.error(e); }
                },

                selectOrigin(area) {
                    this.ratesForm.origin = area.id;
                    this.originSearch = area.name;
                    this.originResults = [];
                },

                async searchDest() {
                    if (this.destSearch.length < 3) return;
                    try {
                        const res = await fetch(`/shipping/areas?q=${this.destSearch}`);
                        const data = await res.json();
                        this.destResults = data.areas || [];
                    } catch (e) { console.error(e); }
                },

                selectDest(area) {
                    this.ratesForm.destination = area.id;
                    this.destSearch = area.name;
                    this.destResults = [];
                },

                async getRates() {
                    this.loadingRates = true;
                    this.ratesResults = [];
                    try {
                        const res = await fetch('/admin/tools/rates', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            body: JSON.stringify(this.ratesForm)
                        });
                        const data = await res.json();
                        this.ratesResults = data.pricing || [];
                    } catch (e) { console.error(e); }
                    finally { this.loadingRates = false; }
                },

                formatTimelineDate(dateStr) {
                    if (!dateStr) return '';
                    try {
                        const d = new Date(dateStr);
                        if (isNaN(d.getTime())) return dateStr;
                        const day = d.getDate();
                        const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                        const month = months[d.getMonth()];
                        const year = d.getFullYear();
                        const hours = String(d.getHours()).padStart(2, '0');
                        const mins = String(d.getMinutes()).padStart(2, '0');
                        return `${day} ${month} ${year} - ${hours}:${mins}`;
                    } catch (e) {
                        return dateStr;
                    }
                },

                async trackResi() {
                    if (!this.trackForm.waybill) return;
                    this.loadingTrack = true;
                    this.trackResult = null;
                    try {
                        const res = await fetch(`/admin/tools/track/${this.trackForm.waybill}`);
                        const data = await res.json();
                        this.trackResult = data;
                    } catch (e) { console.error(e); }
                    finally { this.loadingTrack = false; }
                }
            }
        }
    </script>
</x-app-layout>
