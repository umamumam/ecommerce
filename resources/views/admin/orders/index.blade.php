<x-app-layout>
    <!-- Import SweetAlert2 for beautiful alerts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold py-3 mb-0">
                <span class="text-muted fw-light">Logistik /</span> Kelola Pesanan
            </h4>
            <div class="d-flex align-items-center gap-2">
                <form action="{{ route('admin.orders.syncStatus') }}" method="POST" id="sync-form">
                    @csrf
                    <button type="submit" class="btn btn-success shadow-sm rounded-pill">
                        <i class="ti ti-refresh me-1"></i> SINKRONKAN STATUS
                    </button>
                </form>
                
                <div id="bulk-actions" class="d-none animate__animated animate__fadeIn">
                    <div class="btn-group shadow-sm">
                        <button type="button" class="btn btn-primary dropdown-toggle rounded-pill" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="ti ti-layout-grid me-1"></i> AKSI CEPAT (<span id="selected-count">0</span>)
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="javascript:void(0);" onclick="bulkPrint()">
                                    <i class="ti ti-printer me-2 text-primary"></i> Cetak Label Massal
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center text-muted" href="javascript:void(0);">
                                    <i class="ti ti-box me-2"></i> Kemas Pesanan (Coming Soon)
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Card -->
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-body">
                <form action="{{ route('admin.orders.index') }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label small fw-bold">DARI TANGGAL</label>
                        <input type="date" name="start_date" class="form-control border-light" value="{{ $startDate }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold">SAMPAI TANGGAL</label>
                        <input type="date" name="end_date" class="form-control border-light" value="{{ $endDate }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold">STATUS</label>
                        <select name="status" class="form-select border-light">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid (Siap Kirim)</option>
                            <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing (Kemas)</option>
                            <option value="shipping" {{ request('status') == 'shipping' ? 'selected' : '' }}>Shipping (Kirim)</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-info w-100"><i class="ti ti-filter me-1"></i> FILTER</button>
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary w-50">RESET</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="card border-0 shadow-sm overflow-hidden">
            <div class="table-responsive text-nowrap">
                <table class="table table-hover align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th width="40"><input class="form-check-input" type="checkbox" id="check-all"></th>
                            <th class="small fw-bold">PESANAN</th>
                            <th class="small fw-bold">PELANGGAN</th>
                            <th class="small fw-bold">LOKASI</th>
                            <th class="small fw-bold">TOTAL</th>
                            <th class="small fw-bold">STATUS</th>
                            <th class="small fw-bold text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse($orders as $order)
                        <tr>
                            <td>
                                <input class="form-check-input order-checkbox" type="checkbox" value="{{ $order->id }}">
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="fw-bold text-primary mb-0">#{{ $order->code }}</a>
                                    <small class="text-muted">{{ $order->created_at->format('d M Y, H:i') }}</small>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-2">
                                        <span class="avatar-initial rounded-circle bg-label-secondary text-uppercase">{{ substr($order->user->name, 0, 1) }}</span>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="fw-medium small">{{ $order->user->name }}</span>
                                        <small class="text-muted" style="font-size: 10px">{{ $order->user->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="small">{{ $order->shipping_city ?: 'Data Lokasi' }}</span>
                                    <span class="badge bg-label-info p-1" style="font-size: 9px; width: fit-content">{{ strtoupper($order->shipping_courier) }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="fw-bold text-dark small">Rp{{ number_format($order->grand_total, 0, ',', '.') }}</span>
                            </td>
                            <td>
                                @php
                                    $statusClass = [
                                        'pending' => 'bg-label-warning',
                                        'paid' => 'bg-label-success',
                                        'processing' => 'bg-label-info',
                                        'shipping' => 'bg-label-primary',
                                        'completed' => 'bg-label-success',
                                        'cancelled' => 'bg-label-danger'
                                    ][$order->status] ?? 'bg-label-secondary';
                                @endphp
                                <span class="badge {{ $statusClass }} rounded-pill" style="font-size: 10px">{{ strtoupper($order->status) }}</span>
                            </td>
                            <td class="text-center">
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="ti ti-dots-vertical"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-menu-item d-flex align-items-center py-2 px-3" href="{{ route('admin.orders.show', $order->id) }}">
                                            <i class="ti ti-eye me-2 text-info"></i> DETAIL
                                        </a>
                                        @if($order->status == 'paid')
                                        <form action="{{ route('admin.orders.shipment', $order->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item d-flex align-items-center py-2 px-3">
                                                <i class="ti ti-truck me-2 text-primary"></i> PROSES KIRIM
                                            </button>
                                        </form>
                                        @endif
                                        @if($order->shipping_waybill)
                                        <a class="dropdown-menu-item d-flex align-items-center py-2 px-3" href="{{ route('admin.orders.label', $order->id) }}" target="_blank">
                                            <i class="ti ti-printer me-2 text-info"></i> CETAK RESI
                                        </a>
                                        @endif
                                        <div class="dropdown-divider"></div>
                                        <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Hapus pesanan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item d-flex align-items-center py-2 px-3 text-danger">
                                                <i class="ti ti-trash me-2"></i> HAPUS
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="ti ti-package-off display-4 text-muted mb-3 d-block"></i>
                                <h6 class="text-muted">Tidak ada pesanan ditemukan</h6>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white border-top py-3">
                {{ $orders->appends(request()->query())->links() }}
            </div>
        </div>
    </div>

    <!-- Scripts for Bulk Action -->
    @push('scripts')
    <script>
        const checkAll = document.getElementById('check-all');
        const checkboxes = document.querySelectorAll('.order-checkbox');
        const bulkAction = document.getElementById('bulk-actions');
        const selectedCount = document.getElementById('selected-count');

        function updateBulkStats() {
            const checkedCount = document.querySelectorAll('.order-checkbox:checked').length;
            selectedCount.textContent = checkedCount;
            if (checkedCount > 0) {
                bulkAction.classList.remove('d-none');
            } else {
                bulkAction.classList.add('d-none');
            }
        }

        checkAll.addEventListener('change', function() {
            checkboxes.forEach(cb => {
                cb.checked = checkAll.checked;
            });
            updateBulkStats();
        });

        checkboxes.forEach(cb => {
            cb.addEventListener('change', updateBulkStats);
        });

        function bulkPrint() {
            const selectedIds = Array.from(document.querySelectorAll('.order-checkbox:checked')).map(cb => cb.value);
            
            if (selectedIds.length === 0) return;

            Swal.fire({
                title: 'Sedang memproses...',
                text: 'Mohon tunggu sebentar sementara kami menyiapkan label massal Anda.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch('{{ route("admin.orders.bulkLabel") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ ids: selectedIds })
            })
            .then(res => res.json())
            .then(data => {
                Swal.close();
                if (data.url) {
                    window.open(data.url, '_blank');
                } else {
                    Swal.fire('Gagal', data.message || 'Terjadi kesalahan sistem', 'error');
                }
            })
            .catch(err => {
                Swal.close();
                Swal.fire('Error', 'Gagal menghubungi server', 'error');
            });
        }
    </script>
    @endpush
</x-app-layout>
