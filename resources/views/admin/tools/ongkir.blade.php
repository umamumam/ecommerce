<x-app-layout>
    <div class="container-xxl flex-grow-1 container-p-y" x-data="ongkirTools()">
        <h4 class="fw-bold py-3 mb-4 uppercase tracking-tighter">Logistics Suite / Cek Ongkir</h4>

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
                            <input type="text" x-model="originSearch" @input.debounce.500ms="searchOrigin()" 
                                placeholder="Cari kecamatan asal..." 
                                class="form-control h-12 rounded-2xl border-slate-100 text-xs font-bold focus:border-[#006d5b] focus:ring-4 focus:ring-[#006d5b]/5">
                            
                            <div x-show="originResults.length > 0" class="absolute left-0 right-0 mt-2 bg-white rounded-2xl shadow-2xl border border-slate-100 z-[60] max-h-48 overflow-y-auto no-scrollbar">
                                <template x-for="area in originResults" :key="area.id">
                                    <div @click="selectOrigin(area)" class="px-4 py-3 hover:bg-slate-50 cursor-pointer border-b border-slate-50 last:border-0">
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
                                class="form-control h-12 rounded-2xl border-slate-100 text-xs font-bold focus:border-[#006d5b] focus:ring-4 focus:ring-[#006d5b]/5">
                            
                            <div x-show="destResults.length > 0" class="absolute left-0 right-0 mt-2 bg-white rounded-2xl shadow-2xl border border-slate-100 z-[60] max-h-48 overflow-y-auto no-scrollbar">
                                <template x-for="area in destResults" :key="area.id">
                                    <div @click="selectDest(area)" class="px-4 py-3 hover:bg-slate-50 cursor-pointer border-b border-slate-50 last:border-0">
                                        <span class="text-[11px] font-black block text-slate-900" x-text="area.name"></span>
                                        <span class="text-[9px] text-slate-400" x-text="area.formatted_address"></span>
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
                        <button @click="getRates()" class="btn btn-dark w-full h-12 rounded-2xl font-black uppercase tracking-widest text-[10px] shadow-xl group transition-all" :disabled="loadingRates || !ratesForm.origin || !ratesForm.destination">
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
                <div x-show="ratesResults.length > 0" class="card glass-card p-10 shadow-2xl border-0 h-full overflow-hidden animate-fade-left">
                    <div class="flex items-center gap-4 mb-10 border-b pb-6">
                        <div class="w-12 h-12 bg-emerald-50 text-[#006d5b] rounded-2xl flex items-center justify-center font-black">
                            <i class="ti ti-truck-delivery text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-black uppercase tracking-tighter text-slate-900 leading-none mb-1">Hasil Perbandingan Tarif</h3>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Ditemukan <span x-text="ratesResults.length"></span> opsi pengiriman</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-h-[600px] overflow-y-auto pr-4 no-scrollbar">
                        <template x-for="rate in ratesResults" :key="rate.courier_code + rate.courier_service_code">
                            <div class="p-6 border-2 border-slate-50 rounded-[2rem] bg-white hover:border-[#006d5b] hover:shadow-xl transition-all group relative overflow-hidden">
                                <div class="flex items-center justify-between mb-6">
                                    <img :src="'https://biteship.com/assets/images/courier-logos/' + rate.courier_code + '.png'" class="h-5 grayscale group-hover:grayscale-0 transition" :alt="rate.courier_name" onerror="this.src='https://via.placeholder.com/100x40?text=Kurir'">
                                    <div class="text-right">
                                        <span class="text-lg font-black text-[#006d5b] block" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(rate.price)"></span>
                                        <div class="flex items-center justify-end gap-2 mt-1">
                                            <template x-if="rate.available_for_cash_on_delivery">
                                                <span class="bg-amber-100 text-amber-700 text-[7px] font-black px-1.5 py-0.5 rounded uppercase tracking-widest">COD</span>
                                            </template>
                                            <span class="text-[8px] font-black text-slate-300 uppercase tracking-widest" x-text="rate.duration"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex flex-col">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="text-xs font-black uppercase text-slate-900" x-text="rate.courier_name"></span>
                                        <span class="text-[8px] font-black px-2 py-0.5 rounded-full bg-slate-100 text-slate-400 uppercase tracking-tighter" x-text="rate.type"></span>
                                    </div>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter" x-text="rate.courier_service_name"></span>
                                </div>
                                <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:opacity-10 transition-opacity">
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
                ratesForm: { origin: '', destination: '', weight: 1000 },
                
                originSearch: '',
                originResults: [],
                
                destSearch: '',
                destResults: [],

                ratesResults: [],

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
                }
            }
        }
    </script>
</x-app-layout>
