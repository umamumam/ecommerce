<x-app-layout>
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Sistem /</span> Integrasi & API
        </h4>

        <div class="row g-4">
            <!-- Biteship Integration -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 20px">
                    <div class="card-header bg-white border-0 pt-4 px-4 d-flex align-items-center gap-3">
                        <div class="avatar avatar-md bg-label-info p-2 rounded-3">
                            <i class="ti ti-truck fs-3"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold">Biteship Integration</h5>
                            <small class="text-muted">Kelola pengiriman & kurir otomatis</small>
                        </div>
                    </div>
                    <div class="card-body px-4">
                        <form action="{{ route('admin.integration.updateKeys') }}" method="POST" class="mb-4">
                            @csrf
                            <input type="hidden" name="provider" value="biteship">
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-uppercase">Biteship API Key</label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text border-light bg-light"><i class="ti ti-key"></i></span>
                                    <input type="password" name="biteship_api_key" class="form-control border-light bg-light" 
                                        value="{{ config('services.biteship.key') }}" placeholder="biteship_test_...">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-info w-100 shadow-sm border-0">UPDATE API KEY</button>
                        </form>

                        <hr class="border-light opacity-50 mb-4">

                        <h6 class="fw-bold small text-uppercase mb-3">Origin / Gudang Toko</h6>
                        <form action="{{ route('admin.integration.updateOrigin') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label small">Origin ID (Biteship)</label>
                                <input type="text" name="biteship_origin_id" class="form-control border-light bg-light" 
                                    value="{{ config('services.biteship.origin_id') }}" placeholder="IDNP3CL...">
                                <div class="form-text mt-2" style="font-size: 10px">
                                    <i class="ti ti-info-circle me-1"></i> Origin ID didapatkan dari dashboard Biteship bagian Area.
                                </div>
                            </div>
                            <button type="submit" class="btn btn-outline-info w-100 border-2">SIMPAN LOKASI ASAL</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Xendit Integration -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 20px">
                    <div class="card-header bg-white border-0 pt-4 px-4 d-flex align-items-center gap-3">
                        <div class="avatar avatar-md bg-label-primary p-2 rounded-3">
                            <i class="ti ti-credit-card fs-3"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold">Xendit Payment</h5>
                            <small class="text-muted">Gerbang pembayaran otomatis</small>
                        </div>
                    </div>
                    <div class="card-body px-4">
                        <form action="{{ route('admin.integration.updateKeys') }}" method="POST">
                            @csrf
                            <input type="hidden" name="provider" value="xendit">
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-uppercase">Xendit Secret Key</label>
                                <div class="input-group input-group-merge mb-3">
                                    <span class="input-group-text border-light bg-light"><i class="ti ti-lock"></i></span>
                                    <input type="password" name="xendit_secret_key" class="form-control border-light bg-light" 
                                        value="{{ config('services.xendit.key') }}" placeholder="xnd_development_...">
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="form-label small fw-bold text-uppercase">Callback Token</label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text border-light bg-light"><i class="ti ti-shield-check"></i></span>
                                    <input type="password" name="xendit_callback_token" class="form-control border-light bg-light" 
                                        value="{{ config('services.xendit.callback_token') }}" placeholder="Token Verifikasi">
                                </div>
                                <div class="form-text mt-2" style="font-size: 10px">
                                    <i class="ti ti-alert-triangle me-1"></i> Jangan bagikan koin ini kepada siapapun untuk menjaga keamanan.
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 shadow-sm border-0">SIMPAN PENGATURAN XENDIT</button>
                        </form>

                        <div class="mt-4 p-3 bg-label-secondary rounded-3 border-dashed">
                            <h6 class="fw-bold small mb-2"><i class="ti ti-webhook me-1"></i> Webhook URL</h6>
                            <code class="small text-primary d-block mb-1">https://shop.ascon.my.id/webhook/xendit</code>
                            <small class="text-muted" style="font-size: 9px">Salin URL ini ke Dashboard Xendit bagian Callbacks.</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>