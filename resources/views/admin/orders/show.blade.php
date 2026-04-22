<x-app-layout>
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold py-3 mb-0">
                <span class="text-muted fw-light">Pesanan /</span> Rincian #{{ $order->code }}
            </h4>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
                <i class="ti ti-arrow-left me-1"></i> KEMBALI
            </a>
        </div>

        <div class="row">
            <!-- Left Column: Details & Items -->
            <div class="col-lg-8">
                <!-- Status Timeline Card -->
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <h5 class="card-title mb-0 fw-bold">Alur Pesanan</h5>
                            @php
                                $statusBadge = [
                                    'pending' => 'bg-label-warning',
                                    'paid' => 'bg-label-success',
                                    'processing' => 'bg-label-info',
                                    'shipping' => 'bg-label-primary',
                                    'completed' => 'bg-label-success'
                                ][$order->status] ?? 'bg-label-secondary';
                            @endphp
                            <span class="badge {{ $statusBadge }} rounded-pill px-3">{{ strtoupper($order->status) }}</span>
                        </div>
                        <div class="d-flex justify-content-between position-relative mt-4">
                            <!-- Progress Line -->
                            <div class="position-absolute top-50 start-0 translate-middle-y w-100 bg-light" style="height: 2px; z-index: 0;"></div>
                            
                            @foreach(['pending', 'paid', 'processing', 'shipping', 'completed'] as $st)
                                @php
                                    $isDone = (array_search($order->status, ['pending', 'paid', 'processing', 'shipping', 'completed']) >= array_search($st, ['pending', 'paid', 'processing', 'shipping', 'completed']));
                                    $icon = [
                                        'pending' => 'ti-shopping-cart',
                                        'paid' => 'ti-wallet',
                                        'processing' => 'ti-package',
                                        'shipping' => 'ti-truck',
                                        'completed' => 'ti-check'
                                    ][$st];
                                @endphp
                                <div class="text-center position-relative" style="z-index: 1;">
                                    <div class="avatar avatar-md mx-auto mb-2 {{ $isDone ? 'bg-primary shadow-sm' : 'bg-light' }}">
                                        <span class="avatar-initial rounded-circle {{ $isDone ? 'text-white' : 'text-muted' }}">
                                            <i class="ti {{ $icon }}"></i>
                                        </span>
                                    </div>
                                    <small class="fw-bold d-block text-uppercase" style="font-size: 9px">{{ $st }}</small>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Product Items Card -->
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
                                                $img = Str::startsWith($item->product->image, 'http') ? $item->product->image : asset('storage/'.$item->product->image);
                                            } elseif ($item->product->images && is_array($item->product->images) && count($item->product->images) > 0) {
                                                $firstImg = $item->product->images[0];
                                                $img = Str::startsWith($firstImg, 'http') ? $firstImg : asset('storage/'.$firstImg);
                                            }
                                        @endphp
                                        <div class="avatar avatar-lg">
                                            <img src="{{ $img }}" class="rounded shadow-sm">
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-dark d-block mb-1">{{ $item->product->name }}</span>
                                        <small class="text-muted">Varian: {{ $item->variant_2 ?: 'Standard' }}</small>
                                    </td>
                                    <td>
                                        <span class="text-muted small">x{{ $item->quantity }}</span>
                                    </td>
                                    <td class="pe-4 text-end">
                                        <span class="fw-bold">Rp{{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Billing Information Card -->
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
                            <h4 class="fw-bold mb-0 text-primary">Rp{{ number_format($order->grand_total, 0, ',', '.') }}</h4>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Customer & Shipping -->
            <div class="col-lg-4">
                <!-- Customer Info -->
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px">
                    <div class="card-body p-4">
                        <h6 class="fw-bold text-uppercase small text-muted mb-3">Informasi Pelanggan</h6>
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar avatar-md me-3">
                                <span class="avatar-initial rounded-circle bg-label-primary">{{ substr($order->user->name, 0, 1) }}</span>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold">{{ $order->user->name }}</h6>
                                <small class="text-muted">{{ $order->user->email ?? 'no-email@store.com' }}</small>
                            </div>
                        </div>
                        <p class="small text-muted mb-0"><i class="ti ti-phone me-1"></i> {{ $order->user->phone ?? 'Tidak ada telepon' }}</p>
                    </div>
                </div>

                <!-- Logistics Info -->
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold text-uppercase small text-muted mb-0">Pengiriman</h6>
                            <span class="badge bg-label-info">{{ strtoupper($order->shipping_courier) }}</span>
                        </div>
                        <div class="bg-light p-3 rounded-3 mb-3 border-dashed border-info">
                            <small class="text-muted d-block mb-1">Nomor Resi</small>
                            <h5 class="fw-bold mb-0 font-mono">{{ $order->shipping_waybill ?: 'Belum Ada' }}</h5>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted d-block mb-1 text-uppercase" style="font-size: 10px">Alamat Penerima</small>
                            <p class="small fw-medium mb-1">{{ $order->shipping_name }}</p>
                            <p class="small text-muted mb-0">{{ $order->shipping_address }}</p>
                        </div>
                        
                        <!-- Actions -->
                        <div class="d-grid gap-2 mt-4">
                            @if($order->status == 'paid')
                            <form action="{{ route('admin.orders.shipment', $order->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary w-100 py-2">
                                    <i class="ti ti-truck-delivery me-1"></i> PROSES KIRIM SEKARANG
                                </button>
                            </form>
                            @endif

                            @if($order->shipping_waybill)
                            <a href="{{ route('admin.orders.label', $order->id) }}" target="_blank" class="btn btn-outline-info py-2">
                                <i class="ti ti-printer me-1"></i> CETAK LABEL RESI
                            </a>
                            @if($order->biteship_tracking_link)
                            <a href="{{ $order->biteship_tracking_link }}" target="_blank" class="btn btn-outline-success py-2">
                                <i class="ti ti-track me-1"></i> PELACAKAN REALTIME
                            </a>
                            @endif
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Admin Notes -->
                <div class="card border-0 shadow-sm" style="border-radius: 15px">
                    <div class="card-body p-4">
                        <h6 class="fw-bold text-uppercase small text-muted mb-3">ID Logistik</h6>
                        <div class="bg-light p-3 rounded-2">
                            <code class="small text-muted">{{ $order->biteship_order_id ?: 'Order belum dipush ke Biteship' }}</code>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
