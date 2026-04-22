<x-app-layout>
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Order /</span> Detail #{{ $order->code }}
        </h4>

        <div class="row">
            <!-- Order Info & Items -->
            <div class="col-xl-8 col-lg-7 col-md-12">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Rincian Produk</h5>
                        <span class="badge bg-label-{{ $order->status == 'pending' ? 'warning' : ($order->status == 'cancelled' ? 'secondary' : 'success') }}">
                            {{ strtoupper($order->status) }}
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table border-top">
                                <thead>
                                    <tr>
                                        <th>Produk</th>
                                        <th>Harga</th>
                                        <th>Qty</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->details as $detail)
                                    <tr>
                                        <td>
                                            <div class="d-flex justify-content-start align-items-center">
                                                <div class="avatar-wrapper">
                                                    <div class="avatar me-2">
                                                        <img src="{{ $detail->product->image ? (Str::startsWith($detail->product->image, 'http') ? $detail->product->image : asset('storage/'.$detail->product->image)) : asset('assets/img/elements/1.jpg') }}" alt="Avatar" class="rounded-circle">
                                                    </div>
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <span class="text-body text-truncate fw-semibold">{{ $detail->product->name }}</span>
                                                    <small class="text-muted">{{ $detail->variant_2 ?: 'Standard' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>Rp{{ number_format($detail->price, 0, ',', '.') }}</td>
                                        <td>{{ $detail->quantity }}</td>
                                        <td class="fw-bold">Rp{{ number_format($detail->price * $detail->quantity, 0, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-end align-items-center mt-3">
                            <div class="order-calculations">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="w-px-150 text-heading">Subtotal:</span>
                                    <h6 class="mb-0">Rp{{ number_format($order->total_price, 0, ',', '.') }}</h6>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="w-px-150 text-heading">Shipping:</span>
                                    <h6 class="mb-0">Rp{{ number_format($order->shipping_price, 0, ',', '.') }}</h6>
                                </div>
                                <div class="d-flex justify-content-between border-top pt-2">
                                    <h6 class="w-px-150 mb-0">Total:</h6>
                                    <h5 class="mb-0 text-primary">Rp{{ number_format($order->grand_total, 0, ',', '.') }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Shipping Tracking -->
                @if($order->status == 'shipping' || $order->status == 'completed' || $order->biteship_order_id)
                <div class="card mb-4 mt-4">
                    <div class="card-header">
                        <h5 class="mb-0">Pengiriman & Pelacakan</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-1 text-muted">Ekspedisi:</p>
                                <h6 class="mb-3">{{ strtoupper($order->shipping_courier) }} ({{ $order->shipping_service }})</h6>
                                
                                <p class="mb-1 text-muted">No. Resi:</p>
                                <div class="d-flex align-items-center gap-2">
                                    <h6 class="mb-0 fw-bold">{{ $order->shipping_waybill ?: 'Sedang diproses' }}</h6>
                                    @if($order->shipping_waybill)
                                    <button class="btn btn-sm btn-outline-primary py-0" onclick="navigator.clipboard.writeText('{{ $order->shipping_waybill }}')">COPY</button>
                                    <a href="{{ route('admin.orders.label', $order->id) }}" target="_blank" class="btn btn-sm btn-outline-info py-0">
                                        <i class="ti ti-printer me-1"></i> CETAK RESI
                                    </a>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1 text-muted">Biteship Order ID:</p>
                                <small class="font-mono text-muted">{{ $order->biteship_order_id }}</small>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Customer & Action -->
            <div class="col-xl-4 col-lg-5 col-md-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Detail Pelanggan</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-start align-items-center mb-4">
                            <div class="avatar me-2">
                                <img src="{{ $order->user->profile_photo ? asset('storage/'.$order->user->profile_photo) : asset('assets/img/avatars/1.png') }}" alt="Avatar" class="rounded-circle">
                            </div>
                            <div class="d-flex flex-column">
                                <h6 class="mb-0">{{ $order->user->name }}</h6>
                                <small class="text-muted">Customer ID: #{{ $order->user_id }}</small>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-start align-items-center mb-4">
                            <span class="avatar rounded-circle bg-label-success me-2 flex-shrink-0"><i class="ti ti-mail ti-sm"></i></span>
                            <h6 class="mb-0">{{ $order->user->email }}</h6>
                        </div>
                        <div class="d-flex justify-content-start align-items-center">
                            <span class="avatar rounded-circle bg-label-success me-2 flex-shrink-0"><i class="ti ti-phone ti-sm"></i></span>
                            <h6 class="mb-0">{{ $order->shipping_phone }}</h6>
                        </div>

                        <hr class="my-4">

                        <h6 class="mb-3 tracking-tighter text-muted text-uppercase small">Alamat Pengiriman</h6>
                        <p class="mb-0">{{ $order->shipping_name }}</p>
                        <p class="text-muted mb-0">{{ $order->shipping_address }}</p>
                        <p class="text-muted">Kodepos: {{ $order->shipping_postal_code }}</p>
                    </div>
                </div>

                <!-- Admin Action Card -->
                <div class="card border-primary border shadow-none">
                    <div class="card-header bg-primary py-3">
                        <h6 class="text-white mb-0">Admin Action</h6>
                    </div>
                    <div class="card-body pt-4">
                        @if($order->status == 'paid')
                        <div class="mb-3">
                            <p class="small text-muted mb-2">Buat pengiriman ke Biteship untuk mendapatkan kurir.</p>
                            <form action="{{ route('admin.orders.shipment', $order->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="ti ti-truck me-1"></i> LANJUTKAN KE PENGIRIMAN
                                </button>
                            </form>
                        </div>
                        @elseif($order->status == 'pending')
                         <div class="alert alert-warning d-flex align-items-center" role="alert">
                            <span class="alert-icon text-warning me-2">
                                <i class="ti ti-bell ti-xs"></i>
                            </span>
                            Menunggu pembayaran dari pelanggan.
                        </div>
                        @else
                        <div class="text-center">
                            <p class="text-muted small">Status: <strong>{{ strtoupper($order->status) }}</strong></p>
                            <i class="ti ti-check text-success display-4"></i>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
