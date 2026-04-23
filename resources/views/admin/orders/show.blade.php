<x-app-layout>
    <style>
        /* --- Custom Timeline Styles --- */
        .timeline-wrapper {
            position: relative;
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            padding: 0 10px;
        }

        /* Garis abu-abu background */
        .timeline-line-bg {
            position: absolute;
            top: 20px;
            /* Setengah dari tinggi circle (40px) */
            left: 5%;
            right: 5%;
            height: 3px;
            background-color: #e2e8f0;
            z-index: 1;
        }

        /* Garis warna progress */
        .timeline-line-progress {
            position: absolute;
            top: 20px;
            left: 5%;
            height: 3px;
            background-color: #7367f0;
            /* Warna primary Vuexy */
            z-index: 2;
            transition: width 0.5s ease-in-out;
        }

        .timeline-step {
            position: relative;
            z-index: 3;
            text-align: center;
            width: 70px;
        }

        .timeline-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #fff;
            border: 3px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 8px auto;
            color: #a8aaae;
            font-size: 1.2rem;
            transition: all 0.3s ease;
        }

        /* State ketika step sudah dilewati/selesai */
        .timeline-step.done .timeline-circle {
            background-color: #7367f0;
            border-color: #7367f0;
            color: #fff;
            box-shadow: 0 0 12px rgba(115, 103, 240, 0.4);
        }

        /* State ketika step sedang aktif (current) */
        .timeline-step.active .timeline-circle {
            background-color: #fff;
            border-color: #7367f0;
            color: #7367f0;
            box-shadow: 0 0 0 4px rgba(115, 103, 240, 0.15);
        }

        .timeline-label {
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            color: #64748b;
        }

        .timeline-step.done .timeline-label,
        .timeline-step.active .timeline-label {
            color: #1e293b;
        }
    </style>

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold py-3 mb-0">
                <span class="text-muted fw-light">Pesanan /</span> Rincian #{{ $order->code }}
            </h4>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary rounded-pill">
                <i class="ti ti-arrow-left me-1"></i> KEMBALI
            </a>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between mb-5">
                            <h5 class="card-title mb-0 fw-bold">Alur Pesanan</h5>
                            @php
                            $statusBadge = [
                            'pending' => 'bg-label-warning',
                            'paid' => 'bg-label-success',
                            'processing' => 'bg-label-info',
                            'shipping' => 'bg-label-primary',
                            'completed' => 'bg-label-success'
                            ][$order->status] ?? 'bg-label-secondary';

                            $statuses = ['pending', 'paid', 'processing', 'shipping', 'completed'];
                            $currentIndex = array_search($order->status, $statuses);
                            if ($currentIndex === false) $currentIndex = 0;

                            // Menghitung lebar garis progress (0% - 100%)
                            $progressWidth = ($currentIndex / (count($statuses) - 1)) * 90;
                            @endphp
                            <span
                                class="badge {{ $statusBadge }} rounded-pill px-3 py-2 fw-bold text-uppercase tracking-widest">{{
                                $order->status }}</span>
                        </div>

                        <div class="timeline-wrapper px-md-4">
                            <div class="timeline-line-bg"></div>
                            <div class="timeline-line-progress" style="width: {{ $progressWidth }}%;"></div>

                            @foreach($statuses as $index => $st)
                            @php
                            $isDone = $index < $currentIndex; $isActive=$index===$currentIndex; $stateClass=$isDone
                                ? 'done' : ($isActive ? 'active' : '' ); $icon=[ 'pending'=> 'ti-shopping-cart',
                                'paid' => 'ti-wallet',
                                'processing' => 'ti-package',
                                'shipping' => 'ti-truck',
                                'completed' => 'ti-check'
                                ][$st];
                                @endphp
                                <div class="timeline-step {{ $stateClass }}">
                                    <div class="timeline-circle">
                                        <i class="ti {{ $icon }}"></i>
                                    </div>
                                    <div class="timeline-label">{{ $st }}</div>
                                </div>
                                @endforeach
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="card-title mb-0 fw-bold">Item Terbeli</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <tbody class="table-border-bottom-0">
                                @foreach($order->details as $item)
                                <tr>
                                    <td width="80" class="ps-4">
                                        @php
                                        $img = asset('assets/img/elements/1.jpg');
                                        if ($item->product->image) {
                                        $img = Str::startsWith($item->product->image, 'http') ? $item->product->image :
                                        asset('storage/'.$item->product->image);
                                        } elseif ($item->product->images && is_array($item->product->images) &&
                                        count($item->product->images) > 0) {
                                        $firstImg = $item->product->images[0];
                                        $img = Str::startsWith($firstImg, 'http') ? $firstImg :
                                        asset('storage/'.$firstImg);
                                        }
                                        @endphp
                                        <div class="avatar avatar-lg">
                                            <img src="{{ $img }}" class="rounded shadow-sm" style="object-fit: cover;">
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-dark d-block mb-1">{{ $item->product->name }}</span>
                                        <small class="text-muted">Varian: {{ $item->variant_2 ?: 'Standard' }}</small>
                                    </td>
                                    <td>
                                        <span class="text-muted small fw-bold">x{{ $item->quantity }}</span>
                                    </td>
                                    <td class="pe-4 text-end">
                                        <span class="fw-bold text-primary">Rp{{ number_format($item->price *
                                            $item->quantity, 0, ',', '.') }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">Ringkasan Pembayaran</h5>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Subtotal Produk</span>
                            <span class="fw-medium">Rp{{ number_format($order->total_price, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Ongkos Kirim</span>
                            <span class="fw-medium">Rp{{ number_format($order->shipping_price, 0, ',', '.') }}</span>
                        </div>
                        <hr class="my-3 opacity-50">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="fw-bold mb-0">Total Tagihan</h5>
                            <h4 class="fw-bold mb-0 text-primary">Rp{{ number_format($order->grand_total, 0, ',', '.')
                                }}</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px">
                    <div class="card-body p-4">
                        <h6 class="fw-bold text-uppercase small text-muted mb-3 d-flex align-items-center gap-2">
                            <i class="ti ti-user text-primary"></i> Informasi Pelanggan
                        </h6>
                        <div class="d-flex align-items-center mb-3 mt-4">
                            <div class="avatar avatar-md me-3">
                                <span class="avatar-initial rounded-circle bg-label-primary fw-bold">{{
                                    substr($order->user->name, 0, 1) }}</span>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold">{{ $order->user->name }}</h6>
                                <small class="text-muted">{{ $order->user->email ?? 'no-email@store.com' }}</small>
                            </div>
                        </div>
                        <div class="bg-light rounded p-2 d-inline-block w-100 mt-2">
                            <p class="small text-dark fw-semibold mb-0 d-flex align-items-center gap-2">
                                <i class="ti ti-phone"></i> {{ $order->user->phone ?? 'Tidak ada telepon' }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h6 class="fw-bold text-uppercase small text-muted mb-0 d-flex align-items-center gap-2">
                                <i class="ti ti-truck text-primary"></i> Pengiriman
                            </h6>
                            <span class="badge bg-label-info fw-bold">{{ strtoupper($order->shipping_courier) }}</span>
                        </div>

                        <div
                            class="bg-label-info bg-opacity-10 p-3 rounded-3 mb-4 border border-info border-dashed d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted d-block mb-1">Nomor Resi</small>
                                <h5 class="fw-bold mb-0 text-dark font-monospace" id="waybillText">
                                    {{ $order->shipping_waybill ?: 'Belum Ada' }}
                                </h5>
                            </div>
                            @if($order->shipping_waybill)
                            <button onclick="copyResi()" id="btnCopy"
                                class="btn btn-icon btn-info shadow-sm rounded-circle" data-bs-toggle="tooltip"
                                data-bs-placement="top" title="Salin Resi">
                                <i class="ti ti-copy" id="copyIcon"></i>
                            </button>
                            @endif
                        </div>

                        <div class="mb-3">
                            <small class="text-muted d-block mb-2 text-uppercase fw-bold"
                                style="font-size: 0.7rem">Alamat Penerima</small>
                            <p class="small fw-bold text-dark mb-1">{{ $order->shipping_name }}</p>
                            <p class="small text-muted mb-0 lh-sm">{{ $order->shipping_address }}</p>
                        </div>

                        <div class="d-grid gap-2 mt-4 pt-2 border-top">
                            @if($order->status == 'paid')
                            <form action="{{ route('admin.orders.shipment', $order->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary w-100 py-2 rounded-pill fw-bold">
                                    <i class="ti ti-package-export me-2"></i> PROSES KIRIM SEKARANG
                                </button>
                            </form>
                            @endif

                            @if($order->shipping_waybill)
                            <a href="{{ route('admin.orders.label', $order->id) }}" target="_blank"
                                class="btn btn-outline-dark py-2 rounded-pill fw-bold">
                                <i class="ti ti-printer me-2"></i> CETAK LABEL RESI
                            </a>
                            <a href="{{ route('admin.orders.printInternal', $order->id) }}" target="_blank"
                                class="btn btn-outline-primary py-2 rounded-pill fw-bold">
                                <i class="ti ti-printer me-2"></i> CETAK INTERNAL (A6)
                            </a>
                            @if($order->biteship_tracking_link)
                            <a href="{{ $order->biteship_tracking_link }}" target="_blank"
                                class="btn btn-success py-2 rounded-pill fw-bold">
                                <i class="ti ti-location me-2"></i> LACAK REALTIME
                            </a>
                            @endif
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm" style="border-radius: 15px">
                    <div class="card-body p-4">
                        <h6 class="fw-bold text-uppercase small text-muted mb-3 d-flex align-items-center gap-2">
                            <i class="ti ti-server text-primary"></i> ID Logistik
                        </h6>
                        <div class="bg-light p-3 rounded-3 border">
                            <code
                                class="small text-muted d-block text-break">{{ $order->biteship_order_id ?: 'Order belum dipush ke Biteship' }}</code>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function copyResi() {
            // Ambil text resi
            const resiText = document.getElementById('waybillText').innerText.trim();
            if(resiText === 'Belum Ada' || resiText === '') return;

            // Salin ke clipboard
            navigator.clipboard.writeText(resiText).then(function() {
                // Ubah icon untuk feedback visual
                const icon = document.getElementById('copyIcon');
                const btn = document.getElementById('btnCopy');
                
                icon.classList.remove('ti-copy');
                icon.classList.add('ti-check');
                btn.classList.remove('btn-info');
                btn.classList.add('btn-success');

                // Kembalikan ke icon awal setelah 2 detik
                setTimeout(() => {
                    icon.classList.remove('ti-check');
                    icon.classList.add('ti-copy');
                    btn.classList.remove('btn-success');
                    btn.classList.add('btn-info');
                }, 2000);
            }).catch(function(err) {
                console.error('Gagal menyalin text: ', err);
                alert('Gagal menyalin resi.');
            });
        }

        // Auto Refresh logic for "Auto Update" via Webhook detection
        @if(in_array($order->status, ['processing', 'shipping']))
            setTimeout(function() {
                window.location.reload();
            }, 60000); // Refresh every 60 seconds to detect webhook updates
        @endif
    </script>
</x-app-layout>