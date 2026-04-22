<x-app-layout>
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Sistem /</span> Integrasi & API
        </h4>

        <div class="row g-4">
            <!-- Sidebar Info -->
            <div class="col-md-4">
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px">
                    <div class="card-body text-center py-5">
                        <div class="avatar avatar-xl bg-label-primary mx-auto mb-3">
                            <i class="ti ti-shield-check fs-1"></i>
                        </div>
                        <h5 class="fw-bold mb-1">Status Keamanan</h5>
                        <p class="text-muted small">Semua API Key dalam kondisi terenkripsi dan disembunyikan untuk keamanan Anda.</p>
                        <div class="d-flex justify-content-center gap-2 mt-4 text-start">
                            <ul class="list-unstyled small space-y-2">
                                <li><i class="ti ti-check text-success me-2"></i> Biteship Connected</li>
                                <li><i class="ti ti-check text-success me-2"></i> Xendit Verified</li>
                                <li><i class="ti ti-check text-success me-2"></i> Webhook Active</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm" style="border-radius: 20px; background: linear-gradient(135deg, #7367f0 0%, #a098f5 100%);">
                    <div class="card-body p-4 text-white">
                        <h6 class="fw-bold mb-3"><i class="ti ti-webhook me-2"></i>Webhook URL</h6>
                        <p class="small opacity-75">Salin URL ini ke Dashboard Xendit bagian Callbacks agar status pembayaran otomatis terupdate.</p>
                        <div class="bg-white bg-opacity-10 p-3 rounded-3 mb-2">
                            <code class="text-white small d-block break-all" id="webhook-url">https://shop.ascon.my.id/webhook/xendit</code>
                        </div>
                        <button onclick="copyToClipboard('https://shop.ascon.my.id/webhook/xendit')" class="btn btn-white btn-sm w-100 fw-bold">SALIN URL</button>
                    </div>
                </div>
            </div>

            <!-- Main Configurations -->
            <div class="col-md-8">
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-3 mb-4">
                            <div class="avatar bg-label-info p-2 rounded-3">
                                <i class="ti ti-truck fs-3"></i>
                            </div>
                            <h5 class="mb-0 fw-bold">Pengaturan Logistik (Biteship)</h5>
                        </div>

                        <form action="{{ route('admin.integration.updateKeys') }}" method="POST" class="mb-5">
                            @csrf
                            <input type="hidden" name="provider" value="biteship">
                            <div class="mb-4">
                                <label class="form-label small fw-bold">BITESHIP API KEY</label>
                                <div class="input-group">
                                    <input type="password" name="biteship_api_key" class="form-control border-light bg-light" 
                                        value="{{ $accountInfo['biteship_api_key'] }}" placeholder="biteship_test_..." id="b-key">
                                    <button class="btn btn-outline-secondary border-light" type="button" onclick="toggleView('b-key')"><i class="ti ti-eye"></i></button>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-info shadow-none border-0 px-4">SIMPAN KUNCI API</button>
                        </form>

                        <hr class="border-light mb-5">

                        <div class="mb-4">
                            <h6 class="fw-bold mb-3">Origin / Asal Toko</h6>
                            <p class="text-muted small">Cari area atau kecamatan gudang Anda untuk mendapatkan Origin ID secara otomatis.</p>
                            
                            <div class="mb-4 position-relative" x-data="areaSearch()">
                                <label class="form-label small font-bold">CARI KECAMATAN / KOTA</label>
                                <div class="input-group">
                                    <span class="input-group-text border-light bg-light"><i class="ti ti-search"></i></span>
                                    <input type="text" class="form-control border-light bg-light" placeholder="Ketik minimal 3 huruf (misal: Cluwak)" 
                                        @input.debounce.500ms="search($event.target.value)" x-model="query">
                                </div>

                                <!-- Suggestions Dropdown -->
                                <div class="position-absolute w-100 shadow-lg bg-white rounded-3 mt-1 z-index-1 overflow-hidden border" 
                                    x-show="results.length > 0" x-cloak @click.away="results = []">
                                    <template x-for="item in results" :key="item.id">
                                        <div @click="select(item)" class="p-3 border-bottom hover-bg-light cursor-pointer">
                                            <div class="fw-bold small text-dark" x-text="item.name"></div>
                                            <div class="text-muted" style="font-size: 10px" x-text="item.type + ' - ' + item.postal_code"></div>
                                        </div>
                                    </template>
                                </div>

                                <form action="{{ route('admin.integration.updateOrigin') }}" method="POST" class="mt-4">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label small">SELECTED ORIGIN ID</label>
                                        <input type="text" name="biteship_origin_id" class="form-control fw-bold border-light bg-light" 
                                            value="{{ $currentOriginId }}" id="selected-origin-id" readonly>
                                    </div>
                                    <button type="submit" class="btn btn-outline-info border-2 px-4 shadow-none">SET LOKASI UTAMA</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Xendit Card -->
                <div class="card border-0 shadow-sm" style="border-radius: 20px">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-3 mb-4">
                            <div class="avatar bg-label-primary p-2 rounded-3">
                                <i class="ti ti-credit-card fs-3"></i>
                            </div>
                            <h5 class="mb-0 fw-bold">Pengaturan Xendit (Payment)</h5>
                        </div>

                        <form action="{{ route('admin.integration.updateKeys') }}" method="POST">
                            @csrf
                            <input type="hidden" name="provider" value="xendit">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label small fw-bold">XENDIT SECRET KEY</label>
                                    <div class="input-group">
                                        <input type="password" name="xendit_secret_key" class="form-control border-light bg-light" 
                                            value="{{ $accountInfo['xendit_secret_key'] }}" id="x-key">
                                        <button class="btn btn-outline-secondary border-light" type="button" onclick="toggleView('x-key')"><i class="ti ti-eye"></i></button>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label small fw-bold">CALLBACK TOKEN</label>
                                    <input type="password" name="xendit_webhook_token" class="form-control border-light bg-light" 
                                        value="{{ $accountInfo['xendit_webhook_token'] }}">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary px-4 border-0 shadow-none mt-3">SIMPAN PENGATURAN XENDIT</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function toggleView(id) {
            const input = document.getElementById(id);
            input.type = input.type === 'password' ? 'text' : 'password';
        }

        function copyToClipboard(text) {
            navigator.clipboard.writeText(text);
            Swal.fire({
                icon: 'success',
                title: 'Disalin!',
                text: 'Webhook URL berhasil dicopy ke clipboard.',
                timer: 1500,
                showConfirmButton: false
            });
        }

        function areaSearch() {
            return {
                query: '',
                results: [],
                search(val) {
                    if (val.length < 3) {
                        this.results = [];
                        return;
                    }
                    fetch(`{{ route('admin.integration.searchArea') }}?q=${val}`)
                        .then(res => res.json())
                        .then(data => {
                            this.results = data;
                        });
                },
                select(item) {
                    document.getElementById('selected-origin-id').value = item.id;
                    this.query = item.name;
                    this.results = [];
                    Swal.fire({
                        icon: 'info',
                        title: 'Area Terpilih',
                        text: `ID: ${item.id} (${item.name})`,
                        timer: 2000
                    });
                }
            }
        }
    </script>
    @endpush

    <style>
        .hover-bg-light:hover { background-color: #f8f9fa; }
        .cursor-pointer { cursor: pointer; }
    </style>
</x-app-layout>