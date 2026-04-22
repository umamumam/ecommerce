<x-app-layout>
    <style>
        .animate-pulse-custom { animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite; }
        @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: .5; } }
        .animate-gradient-x {
            background-size: 200% 100%;
            animation: gradient-x 2s linear infinite;
        }
        @keyframes gradient-x {
            0% { background-position: 100% 0%; }
            100% { background-position: -100% 0%; }
        }
    </style>

    <div class="container-xxl flex-grow-1 container-p-y" x-data="scanStation()">
        <div class="flex flex-col items-center justify-center min-h-[70vh] gap-8 py-10">
            
            <!-- Header Section -->
            <div class="text-center flex flex-col items-center gap-6">
                <div class="w-20 h-20 bg-[#006d5b] text-white rounded-[2rem] flex items-center justify-center shadow-2xl shadow-[#006d5b]/30 animate-pulse-custom">
                    <i class="ti ti-scan text-4xl"></i>
                </div>
                <div>
                    <h1 class="text-4xl font-black tracking-tighter text-slate-900 mb-2 uppercase">Scan Out Station</h1>
                    <p class="text-slate-400 font-bold uppercase tracking-widest text-[10px]">Gunakan barcode scanner untuk memproses pengiriman</p>
                </div>
            </div>

            <!-- Scanner Card -->
            <div class="w-full max-w-3xl bg-white rounded-[3rem] p-12 shadow-2xl border-4 border-[#006d5b]/5 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-[#006d5b] via-emerald-400 to-[#006d5b] animate-gradient-x"></div>
                
                <form action="{{ url('/admin/tools/scan') }}" method="POST" id="scanForm">
                    @csrf
                    <div class="flex flex-col gap-10 items-center">
                        <div class="w-full relative">
                            <input 
                                type="text" 
                                name="waybill"
                                autofocus
                                autocomplete="off"
                                placeholder="WAITING FOR SCAN..." 
                                class="w-full h-28 text-center text-5xl font-black tracking-[0.2em] bg-slate-50 border-2 border-slate-100 rounded-3xl outline-none focus:border-[#006d5b] focus:ring-8 focus:ring-[#006d5b]/5 transition-all uppercase placeholder:text-slate-200"
                                x-model="waybill"
                                @keydown.enter.prevent="submitScan()"
                            />
                            <div class="absolute -top-4 left-1/2 -translate-x-1/2 bg-[#006d5b] text-white px-5 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest shadow-lg">Target Input</div>
                        </div>

                        <!-- Status Feedbacks -->
                        @if(session('success'))
                            <div class="w-full p-6 bg-emerald-50 border-2 border-emerald-100 rounded-2xl flex items-center gap-5 animate-bounce">
                                <div class="w-12 h-12 bg-emerald-500 text-white rounded-full flex items-center justify-center text-2xl">
                                    <i class="ti ti-circle-check"></i>
                                </div>
                                <div>
                                    <h4 class="text-emerald-900 font-black uppercase tracking-tighter text-lg">Berhasil!</h4>
                                    <p class="text-emerald-600 text-sm font-bold">{{ session('success') }}</p>
                                </div>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="w-full p-6 bg-red-50 border-2 border-red-100 rounded-2xl flex items-center gap-5 animate-pulse">
                                <div class="w-12 h-12 bg-red-500 text-white rounded-full flex items-center justify-center text-2xl">
                                    <i class="ti ti-alert-circle"></i>
                                </div>
                                <div>
                                    <h4 class="text-red-900 font-black uppercase tracking-tighter text-lg">Gagal!</h4>
                                    <p class="text-red-600 text-sm font-bold">{{ session('error') }}</p>
                                </div>
                            </div>
                        @endif

                        <div x-show="!sessionActive" class="flex items-center gap-4 text-slate-300">
                            <i class="ti ti-loader animate-spin text-2xl"></i>
                            <p class="font-black text-xs uppercase tracking-widest">Silahkan Scan Barcode pada Resi Paket</p>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Stats & Settings -->
            <div class="grid grid-cols-2 md:grid-cols-3 gap-6 w-full max-w-3xl">
                <div class="bg-white p-5 rounded-2xl shadow-xl border border-slate-50 flex flex-col items-center gap-2">
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Auto Update Status</span>
                    <span class="text-[#006d5b] font-black text-xs">SHIPPING (ON)</span>
                </div>
                <div class="bg-white p-5 rounded-2xl shadow-xl border border-slate-50 flex flex-col items-center gap-2">
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Input Locking</span>
                    <span class="text-emerald-500 font-black text-xs">LOCKED TO FOCUS</span>
                </div>
                <div class="hidden md:flex bg-white p-5 rounded-2xl shadow-xl border border-slate-50 flex-col items-center gap-2">
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Sound Feedback</span>
                    <span class="text-slate-300 font-black text-xs">DEFAULT SYSTEM</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        function scanStation() {
            return {
                waybill: '',
                sessionActive: {{ session('success') || session('error') ? 'true' : 'false' }},
                submitScan() {
                    if (this.waybill.length > 5) {
                        document.getElementById('scanForm').submit();
                    }
                }
            }
        }
        
        // Keep focus on the input no matter where you click
        document.addEventListener('click', function() {
            const input = document.querySelector('input[name="waybill"]');
            if (input) input.focus();
        });
    </script>
</x-app-layout>
