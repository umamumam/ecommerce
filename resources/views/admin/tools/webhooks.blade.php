<x-app-layout>
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Logistik /</span> Riwayat Webhook
        </h4>

        <div class="card border-0 shadow-sm overflow-hidden">
            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                <h5 class="card-title mb-0 fw-bold">Incoming Webhooks</h5>
                <span class="badge bg-label-info">Total: {{ $logs->count() }}</span>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th class="small fw-bold">PROVIDER</th>
                            <th class="small fw-bold">EVENT</th>
                            <th class="small fw-bold">WAKTU</th>
                            <th class="small fw-bold">STATUS</th>
                            <th class="small fw-bold text-center">PAYLOAD</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($log->provider == 'xendit')
                                        <span class="badge bg-label-primary p-2 me-2"><i class="ti ti-credit-card"></i></span>
                                    @else
                                        <span class="badge bg-label-info p-2 me-2"><i class="ti ti-truck"></i></span>
                                    @endif
                                    <span class="fw-bold small">{{ strtoupper($log->provider) }}</span>
                                </div>
                            </td>
                            <td><span class="text-muted small">{{ $log->event }}</span></td>
                            <td><small class="text-muted">{{ $log->created_at->format('d M, H:i:s') }}</small></td>
                            <td>
                                @php
                                    $statusClass = [
                                        'received' => 'bg-label-info',
                                        'processed' => 'bg-label-success',
                                        'failed' => 'bg-label-danger'
                                    ][$log->status] ?? 'bg-label-secondary';
                                @endphp
                                <span class="badge {{ $statusClass }} rounded-pill" style="font-size: 10px">{{ strtoupper($log->status) }}</span>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-outline-secondary" 
                                    onclick="viewPayload({{ json_encode($log->payload) }})">
                                    <i class="ti ti-code me-1"></i> VIEW
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">Belum ada riwayat webhook</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal View Payload -->
    <div class="modal fade" id="payloadModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 20px">
                <div class="modal-header border-bottom py-3">
                    <h5 class="modal-title fw-bold">Webhook JSON Payload</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <pre id="json-viewer" class="m-0 p-4 bg-dark text-success" style="max-height: 500px; overflow-y: auto; font-family: 'JetBrains Mono', monospace; font-size: 12px;"></pre>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function viewPayload(data) {
            document.getElementById('json-viewer').textContent = JSON.stringify(data, null, 4);
            new bootstrap.Modal(document.getElementById('payloadModal')).show();
        }
    </script>
    @endpush
</x-app-layout>
