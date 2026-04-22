<x-app-layout>
    <div class="container-xxl flex-grow-1 container-p-y" x-data="resiTools()">
        <h4 class="fw-bold py-3 mb-4 uppercase tracking-tighter">Logistics Suite / Cek Resi</h4>

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
                        <div>
                            <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest mb-3 block">Pilih Kurir</label>
                            <select x-model="trackForm.courier" class="form-select rounded-2xl border-slate-100 text-xs font-bold focus:border-[#006d5b]">
                                <option value="">Auto Detect (Biteship Order)</option>
                                <option value="jne">JNE</option>
                                <option value="jnt">J&T</option>
                                <option value="sicepat">SiCepat</option>
                                <option value="anteraja">AnterAja</option>
                                <option value="tiki">TIKI</option>
                                <option value="pos">POS Indonesia</option>
                            </select>
                        </div>
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

                    <!-- Result Status & General Info (Left Column) -->
                    <div x-show="trackResult" class="mt-8 border-t pt-8 animate-fade-up">
                        <div class="flex items-center justify-between mb-6">
                            <span class="status-badge bg-[#006d5b]/10 text-[#006d5b] px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest" x-text="trackResult.status"></span>
                            <img :src="'https://biteship.com/assets/images/courier-logos/' + trackResult.courier?.company + '.png'" class="h-4 grayscale" :alt="trackResult.courier?.company" onerror="this.style.display='none'">
                        </div>

                        <div class="space-y-6">
                            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                                <span class="text-[9px] font-black uppercase text-slate-400 tracking-widest block mb-2">Pengirim (Origin)</span>
                                <h5 class="text-[11px] font-black text-slate-900 mb-1" x-text="trackResult.origin?.contact_name"></h5>
                                <p class="text-[10px] text-slate-500 font-medium leading-relaxed" x-text="trackResult.origin?.address"></p>
                            </div>

                            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                                <span class="text-[9px] font-black uppercase text-slate-400 tracking-widest block mb-2">Penerima (Destination)</span>
                                <h5 class="text-[11px] font-black text-slate-900 mb-1" x-text="trackResult.destination?.contact_name"></h5>
                                <p class="text-[10px] text-slate-500 font-medium leading-relaxed" x-text="trackResult.destination?.address"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="lg:col-span-2">
                <!-- Illustration State -->
                <div x-show="!trackResult" class="bg-[#006d5b]/5 p-10 rounded-[2.5rem] border-2 border-dashed border-[#006d5b]/20 flex flex-col items-center text-center h-full justify-center">
                    <img src="{{ asset('assets/img/illustrations/page-misc-under-maintenance.png') }}" class="h-48 mb-8" alt="illustration">
                    <h4 class="text-xl font-black text-[#006d5b] uppercase tracking-tighter mb-4">Lacak Pengiriman Anda</h4>
                    <p class="text-slate-500 text-xs font-medium max-w-sm leading-relaxed mb-6">
                        Gunakan fitur ini untuk memantau status pesanan pelanggan secara real-time. Semua data ditarik langsung dari provider kurir melalui jaringan Biteship.
                    </p>
                </div>

                <!-- History State (Right Column) -->
                <div x-show="trackResult" class="card glass-card p-10 shadow-2xl border-0 h-full overflow-hidden animate-fade-left">
                    <div class="flex items-center gap-4 mb-10 border-b pb-6">
                        <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center font-black">
                            <i class="ti ti-history text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-black uppercase tracking-tighter text-slate-900 leading-none mb-1">Detail Perjalanan Paket</h3>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest" x-text="'Resi: ' + trackResult.waybill_id"></p>
                        </div>
                    </div>

                    <div class="space-y-10 relative before:absolute before:left-3 before:top-2 before:bottom-2 before:w-[2px] before:bg-slate-100 max-h-[600px] overflow-y-auto pr-4 no-scrollbar">
                        <template x-for="(history, index) in trackResult.history.slice().reverse()" :key="index">
                            <div class="relative pl-12">
                                <div class="absolute left-0 top-1 w-6 h-6 rounded-full border-4 border-white shadow-xl z-10 flex items-center justify-center"
                                     :class="index === 0 ? 'bg-[#006d5b]' : 'bg-slate-200'">
                                    <div class="w-2 h-2 bg-white rounded-full"></div>
                                </div>
                                <div class="flex flex-col">
                                    <div class="flex items-center gap-3 mb-2">
                                        <span class="text-[11px] font-black uppercase" :class="index === 0 ? 'text-[#006d5b]' : 'text-slate-900'" x-text="history.status"></span>
                                        <span class="text-[9px] font-bold text-slate-400 bg-slate-50 px-2 py-0.5 rounded-full" x-text="new Date(history.updated_at).toLocaleString('id-ID', { day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' })"></span>
                                    </div>
                                    <p class="text-[11px] text-slate-600 font-medium leading-relaxed bg-slate-50/50 p-4 rounded-2xl border border-slate-100" x-text="history.note"></p>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function resiTools() {
            return {
                loadingTrack: false,
                trackForm: { waybill: '', courier: '' },
                trackResult: null,

                async trackResi() {
                    const cleanWaybill = this.trackForm.waybill.trim();
                    if (!cleanWaybill) return;
                    this.loadingTrack = true;
                    this.trackResult = null;
                    try {
                        const res = await fetch(`/admin/tools/track/${cleanWaybill}?courier=${this.trackForm.courier}`);
                        const data = await res.json();
                        this.trackResult = data;
                    } catch (e) { console.error(e); }
                    finally { this.loadingTrack = false; }
                }
            }
        }
    </script>
</x-app-layout>
