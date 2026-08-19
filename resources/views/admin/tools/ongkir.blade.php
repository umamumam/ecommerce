<x-app-layout>
    <div class="container-xxl flex-grow-1 container-p-y" x-data="ongkirTools()">
        <h4 class="fw-bold py-3 mb-4 uppercase tracking-tighter">Logistics Suite / Cek Ongkir</h4>

        <!-- API Error Alert if Any -->
        <div x-show="errorMessage" x-cloak class="mb-4 p-4 rounded-2xl bg-red-50 border border-red-200 text-red-700 text-xs flex items-center justify-between animate-fade-down">
            <div class="flex items-center gap-3">
                <i class="ti ti-alert-triangle text-lg text-red-500"></i>
                <div>
                    <span class="font-bold block">Pemberitahuan API Biteship:</span>
                    <span x-text="errorMessage"></span>
                </div>
            </div>
            <a href="{{ route('admin.integration.index') }}" class="btn btn-xs btn-outline-danger font-bold">Setting API Key</a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-1">
                <!-- Form Cek Ongkir -->
                <div class="card glass-card p-8 shadow-xl h-fit border-0">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-12 h-12 bg-emerald-50 text-[#006d5b] rounded-2xl flex items-center justify-center">
                            <i class="ti ti-calculator text-2xl"></i>
                        </div>
                        <h3 class="text-sm font-black uppercase tracking-widest text-slate-900">Hitung Tarif</h3>
                    </div>

                    <div class="space-y-6">
                        <!-- Wilayah Asal -->
                        <div class="relative">
                            <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest mb-3 block">Wilayah Asal</label>
                            <div class="relative">
                                <input type="text" x-model="originSearch" x-on:input.debounce.400ms="searchOrigin()" 
                                    placeholder="Cari kecamatan asal..." 
                                    class="form-control h-12 rounded-2xl border-slate-100 text-xs font-bold focus:border-[#006d5b] focus:ring-4 focus:ring-[#006d5b]/5 pr-10">
                                <div class="absolute right-3 top-3.5" x-show="loadingOrigin">
                                    <span class="spinner-border spinner-border-sm text-[#006d5b]"></span>
                                </div>
                            </div>
                            
                            <!-- Dropdown Results -->
                            <div x-show="originResults.length > 0" 
                                 x-on:click.outside="originResults = []"
                                 class="absolute left-0 right-0 mt-2 bg-white rounded-2xl shadow-2xl border border-slate-100 z-[60] max-h-48 overflow-y-auto no-scrollbar">
                                <template x-for="area in originResults" :key="area.id">
                                    <div x-on:click="selectOrigin(area)" class="px-4 py-3 hover:bg-slate-50 cursor-pointer border-b border-slate-50 last:border-0">
                                        <span class="text-[11px] font-black block text-slate-900" x-text="area.name"></span>
                                        <span class="text-[9px] text-slate-400" x-text="area.formatted_address || area.name"></span>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Wilayah Tujuan -->
                        <div class="relative">
                            <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest mb-3 block">Wilayah Tujuan</label>
                            <div class="relative">
                                <input type="text" x-model="destSearch" x-on:input.debounce.400ms="searchDest()" 
                                    placeholder="Cari kecamatan tujuan..." 
                                    class="form-control h-12 rounded-2xl border-slate-100 text-xs font-bold focus:border-[#006d5b] focus:ring-4 focus:ring-[#006d5b]/5 pr-10">
                                <div class="absolute right-3 top-3.5" x-show="loadingDest">
                                    <span class="spinner-border spinner-border-sm text-[#006d5b]"></span>
                                </div>
                            </div>
                            
                            <!-- Dropdown Results -->
                            <div x-show="destResults.length > 0" 
                                 x-on:click.outside="destResults = []"
                                 class="absolute left-0 right-0 mt-2 bg-white rounded-2xl shadow-2xl border border-slate-100 z-[60] max-h-48 overflow-y-auto no-scrollbar">
                                <template x-for="area in destResults" :key="area.id">
                                    <div x-on:click="selectDest(area)" class="px-4 py-3 hover:bg-slate-50 cursor-pointer border-b border-slate-50 last:border-0">
                                        <span class="text-[11px] font-black block text-slate-900" x-text="area.name"></span>
                                        <span class="text-[9px] text-slate-400" x-text="area.formatted_address || area.name"></span>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Berat -->
                        <div>
                            <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest mb-3 block">Berat (Gram)</label>
                            <input type="number" x-model="ratesForm.weight" class="form-control h-12 rounded-2xl border-slate-100 text-xs font-bold focus:border-[#006d5b] focus:ring-4 focus:ring-[#006d5b]/5">
                        </div>

                        <!-- Submit -->
                        <button x-on:click="getRates()" class="btn btn-dark w-full h-12 rounded-2xl font-black uppercase tracking-widest text-[10px] shadow-xl group transition-all" :disabled="loadingRates || !ratesForm.origin || !ratesForm.destination">
                            <span x-show="!loadingRates" class="flex items-center justify-center gap-2">
                                <i class="ti ti-search text-base"></i> Cek Ongkos Kirim
                            </span>
                            <span x-show="loadingRates" class="spinner-border spinner-border-sm"></span>
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="lg:col-span-2">
                <!-- Illustration State -->
                <div x-show="ratesResults.length === 0" class="bg-[#006d5b]/5 p-10 rounded-[2.5rem] border-2 border-dashed border-[#006d5b]/20 flex flex-col items-center text-center h-full justify-center">
                    <img src="{{ asset('assets/img/illustrations/page-misc-under-maintenance.png') }}" class="h-48 mb-8" alt="illustration">
                    <h4 class="text-xl font-black text-[#006d5b] uppercase tracking-tighter mb-4">Estimasi Biaya Kirim</h4>
                    <p class="text-slate-500 text-xs font-medium max-w-sm leading-relaxed mb-6">
                        Masukkan wilayah asal dan tujuan untuk melihat perbandingan harga dari berbagai kurir (J&T, JNE, SiCepat, dll).
                    </p>
                </div>

                <!-- Rates Display State -->
                <div x-show="ratesResults.length > 0" class="card glass-card p-8 md:p-10 shadow-2xl border-0 h-full overflow-hidden animate-fade-left">
                    <div class="flex items-center gap-4 mb-8 border-b pb-6">
                        <div class="w-12 h-12 bg-emerald-50 text-[#006d5b] rounded-2xl flex items-center justify-center font-black shrink-0">
                            <i class="ti ti-truck-delivery text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-black uppercase tracking-tighter text-slate-900 leading-none mb-1">Hasil Perbandingan Tarif</h3>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Ditemukan <span x-text="ratesResults.length"></span> opsi pengiriman</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-h-[620px] overflow-y-auto pr-2 no-scrollbar">
                        <template x-for="rate in ratesResults" :key="rate.courier_code + '-' + rate.courier_service_code">
                            <div class="p-5 border-2 border-slate-100/90 rounded-[1.8rem] bg-white hover:border-[#006d5b] hover:shadow-xl transition-all group relative overflow-hidden flex flex-col justify-between min-h-[148px]">
                                <!-- Top Row: Logo & Price -->
                                <div class="flex items-start justify-between gap-3 mb-3">
                                    <!-- Courier Logo Container -->
                                    <div class="h-9 w-28 bg-slate-50/80 border border-slate-100 rounded-xl px-2.5 py-1 flex items-center justify-center shadow-xs shrink-0">
                                        <img :src="rate.courier_logo || ('/assets/img/couriers/' + rate.courier_code + '.webp')" 
                                             class="max-h-7 max-w-full object-contain" 
                                             :alt="rate.courier_name" 
                                             x-on:error="$event.target.src = '/assets/img/couriers/default.svg'">
                                    </div>

                                    <!-- Price & Duration -->
                                    <div class="text-right">
                                        <span class="text-base font-black text-[#006d5b] block leading-tight" x-text="rate.formatted_price || ('Rp ' + new Intl.NumberFormat('id-ID').format(rate.price))"></span>
                                        <div class="flex items-center justify-end gap-1.5 mt-1.5">
                                            <template x-if="rate.available_for_cash_on_delivery">
                                                <span class="bg-amber-100 text-amber-800 text-[8px] font-black px-1.5 py-0.5 rounded uppercase tracking-wider">COD</span>
                                            </template>
                                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-tight flex items-center gap-1">
                                                <i class="ti ti-clock text-[10px] text-slate-300"></i>
                                                <span x-text="rate.formatted_duration || rate.duration"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Bottom Row: Service Info & Category -->
                                <div class="pt-3 border-t border-slate-50 flex flex-col justify-end">
                                    <div class="flex items-center justify-between gap-2 mb-1">
                                        <div class="flex items-center gap-1.5 min-w-0">
                                            <span class="text-xs font-black uppercase text-slate-900 truncate" x-text="rate.courier_service_name || rate.courier_service_code"></span>
                                            <span class="text-[9px] font-bold text-slate-400 uppercase" x-text="'• ' + (rate.courier_code || '').toUpperCase()"></span>
                                        </div>
                                        <span class="text-[8px] font-black px-2 py-0.5 rounded-full uppercase tracking-tight shrink-0" 
                                              :class="{
                                                  'bg-purple-50 text-purple-700 border border-purple-100': rate.category === 'express',
                                                  'bg-amber-50 text-amber-700 border border-amber-100': rate.category === 'cargo',
                                                  'bg-emerald-50 text-emerald-700 border border-emerald-100': rate.category === 'instant',
                                                  'bg-slate-100 text-slate-600': !rate.category || rate.category === 'regular' || rate.category === 'economy'
                                              }"
                                              x-text="rate.category_label || (rate.type ? rate.type.toUpperCase() : 'REGULER')">
                                        </span>
                                    </div>

                                    <p class="text-[10px] text-slate-400 font-medium truncate mb-0 leading-normal" 
                                       x-text="rate.description || 'Layanan pengiriman paket terpercaya'"></p>
                                </div>

                                <!-- Watermark Truck Icon -->
                                <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:opacity-10 transition-opacity pointer-events-none">
                                    <i class="ti ti-truck text-7xl rotate-12"></i>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function ongkirTools() {
            return {
                loadingRates: false,
                loadingOrigin: false,
                loadingDest: false,
                errorMessage: '',
                ratesForm: { origin: '', destination: '', weight: 1000 },
                
                originSearch: '',
                originResults: [],
                
                destSearch: '',
                destResults: [],

                ratesResults: [],

                async searchOrigin() {
                    if (this.originSearch.trim().length < 3) {
                        this.originResults = [];
                        return;
                    }
                    this.loadingOrigin = true;
                    this.errorMessage = '';
                    try {
                        const res = await fetch(`/shipping/areas?q=${encodeURIComponent(this.originSearch)}`);
                        const data = await res.json();
                        if (data.error || data.success === false) {
                            this.errorMessage = data.error || 'Gagal mencari kecamatan. Periksa API Key Biteship.';
                            this.originResults = [];
                        } else {
                            this.originResults = data.areas || [];
                            if (this.originResults.length === 0) {
                                this.errorMessage = 'Tidak ditemukan kecamatan dengan kata kunci tersebut.';
                            }
                        }
                    } catch (e) { 
                        console.error(e); 
                    } finally { 
                        this.loadingOrigin = false; 
                    }
                },

                selectOrigin(area) {
                    this.ratesForm.origin = area.id;
                    this.originSearch = area.name;
                    this.originResults = [];
                    this.errorMessage = '';
                },

                async searchDest() {
                    if (this.destSearch.trim().length < 3) {
                        this.destResults = [];
                        return;
                    }
                    this.loadingDest = true;
                    this.errorMessage = '';
                    try {
                        const res = await fetch(`/shipping/areas?q=${encodeURIComponent(this.destSearch)}`);
                        const data = await res.json();
                        if (data.error || data.success === false) {
                            this.errorMessage = data.error || 'Gagal mencari kecamatan. Periksa API Key Biteship.';
                            this.destResults = [];
                        } else {
                            this.destResults = data.areas || [];
                            if (this.destResults.length === 0) {
                                this.errorMessage = 'Tidak ditemukan kecamatan dengan kata kunci tersebut.';
                            }
                        }
                    } catch (e) { 
                        console.error(e); 
                    } finally { 
                        this.loadingDest = false; 
                    }
                },

                selectDest(area) {
                    this.ratesForm.destination = area.id;
                    this.destSearch = area.name;
                    this.destResults = [];
                    this.errorMessage = '';
                },

                async getRates() {
                    this.loadingRates = true;
                    this.ratesResults = [];
                    this.errorMessage = '';
                    try {
                        const res = await fetch('/admin/tools/rates', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            body: JSON.stringify(this.ratesForm)
                        });
                        const data = await res.json();
                        if (data.error || data.success === false) {
                            this.errorMessage = data.error || 'Gagal menghitung tarif. Periksa API Key Biteship Anda.';
                        } else {
                            let rawRates = data.pricing || [];
                            this.ratesResults = rawRates.map(r => {
                                const code = (r.courier_code || r.company || 'default').toLowerCase();
                                if (!r.courier_logo) {
                                    r.courier_logo = `/assets/img/couriers/${code}.webp`;
                                }
                                if (!r.formatted_price) {
                                    r.formatted_price = 'Rp ' + Number(r.price || 0).toLocaleString('id-ID');
                                }
                                if (!r.formatted_duration) {
                                    let dur = r.duration || '1-3 Hari';
                                    dur = dur.replace(/days/gi, 'Hari').replace(/day/gi, 'Hari').replace(/hours/gi, 'Jam').replace(/hour/gi, 'Jam');
                                    r.formatted_duration = dur;
                                }
                                return r;
                            });
                        }
                    } catch (e) { 
                        console.error(e); 
                    } finally { 
                        this.loadingRates = false; 
                    }
                }
            }
        }
    </script>
</x-app-layout>
