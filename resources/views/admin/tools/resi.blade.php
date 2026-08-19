<x-app-layout>
    <div class="container-xxl flex-grow-1 container-p-y" x-data="resiTools()">
        <h4 class="fw-bold py-3 mb-4 uppercase tracking-tighter">Logistics Suite / Cek Resi</h4>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-1">
                <!-- Cek Resi Form -->
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
                                <option value="ninja">Ninja Xpress</option>
                                <option value="lion">Lion Parcel</option>
                                <option value="sap">SAP Express</option>
                                <option value="wahana">Wahana</option>
                                <option value="ide">IDexpress</option>
                                <option value="paxel">Paxel</option>
                            </select>
                        </div>
                        <div class="relative">
                            <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest mb-3 block">Nomor Resi / Waybill ID</label>
                            <div class="flex gap-2 bg-slate-50 p-2 rounded-2xl border border-slate-100">
                                <input type="text" x-model="trackForm.waybill" @keydown.enter="trackResi()" placeholder="e.g. JB002131..." class="form-control border-0 bg-transparent text-xs font-bold focus:ring-0">
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
                            <div class="h-9 w-24 bg-white p-1.5 rounded-xl border border-slate-100 flex items-center justify-center shadow-xs">
                                <img :src="'/assets/img/couriers/' + (trackResult.courier?.company || 'default') + '.webp'" 
                                     class="max-h-7 max-w-full object-contain" 
                                     :alt="trackResult.courier?.company" 
                                     x-on:error="$event.target.src = '/assets/img/couriers/default.svg'">
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                                <span class="text-[9px] font-black uppercase text-slate-400 tracking-widest block mb-2">Pengirim (Origin)</span>
                                <h5 class="text-[11px] font-black text-slate-900 mb-1" x-text="trackResult.origin?.contact_name || '-'"></h5>
                                <p class="text-[10px] text-slate-500 font-medium leading-relaxed" x-text="trackResult.origin?.address || '-'"></p>
                            </div>

                            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                                <span class="text-[9px] font-black uppercase text-slate-400 tracking-widest block mb-2">Penerima (Destination)</span>
                                <h5 class="text-[11px] font-black text-slate-900 mb-1" x-text="trackResult.destination?.contact_name || '-'"></h5>
                                <p class="text-[10px] text-slate-500 font-medium leading-relaxed" x-text="trackResult.destination?.address || '-'"></p>
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

                <!-- History State (Right Column - Clean Compact Vertical Timeline) -->
                <div x-show="trackResult" class="card glass-card p-8 md:p-10 shadow-2xl border-0 h-full overflow-hidden animate-fade-left">
                    <div class="flex items-center gap-4 mb-8 border-b pb-6">
                        <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center font-black">
                            <i class="ti ti-history text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-black uppercase tracking-tighter text-slate-900 leading-none mb-1">Detail Perjalanan Paket</h3>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest" x-text="'Resi: ' + (trackResult?.waybill_id || trackForm.waybill)"></p>
                        </div>
                    </div>

                    <!-- Clean Vertical Timeline -->
                    <div class="relative pl-3 max-h-[620px] overflow-y-auto pr-4 no-scrollbar">
                        <!-- Continuous Vertical Line -->
                        <div class="absolute left-[22px] top-3 bottom-5 w-[2px] bg-slate-300"></div>

                        <div class="space-y-6">
                            <template x-for="(history, index) in (trackResult?.history || [])" :key="index">
                                <div class="relative flex items-start gap-4">
                                    <!-- Circle Node -->
                                    <div class="w-6 h-6 rounded-full border-2 bg-white flex items-center justify-center shrink-0 z-10"
                                         :class="index === ((trackResult?.history?.length || 1) - 1) ? 'border-[#006d5b] shadow-xs' : 'border-slate-400'">
                                        <div class="w-2 h-2 rounded-full"
                                             :class="index === ((trackResult?.history?.length || 1) - 1) ? 'bg-[#006d5b]' : 'bg-transparent'"></div>
                                    </div>

                                    <!-- Content (Date & Note) -->
                                    <div class="flex flex-col pt-0.5 min-w-0">
                                        <span class="text-xs text-slate-400 font-medium leading-none mb-1.5" 
                                              x-text="formatTimelineDate(history.updated_at)"></span>
                                        <p class="text-xs font-bold text-slate-800 leading-snug mb-0" 
                                           x-text="history.note || history.status || '-'"></p>
                                    </div>
                                </div>
                            </template>
                        </div>
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
