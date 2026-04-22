<x-app-layout>
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h4 class="fw-bold py-1 mb-1 uppercase tracking-tighter">Webhook Audit Logs</h4>
                <p class="text-xs text-slate-400 font-bold uppercase tracking-widest">Pantau riwayat data masuk dari Biteship API</p>
            </div>
            <div class="flex gap-2">
                <span class="bg-indigo-100 text-indigo-600 text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-widest">Live Monitoring</span>
            </div>
        </div>

        <div class="card glass-card shadow-2xl border-0 overflow-hidden">
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="text-[10px] font-black uppercase tracking-widest">ID</th>
                            <th class="text-[10px] font-black uppercase tracking-widest">Event</th>
                            <th class="text-[10px] font-black uppercase tracking-widest">Status</th>
                            <th class="text-[10px] font-black uppercase tracking-widest">Timestamp</th>
                            <th class="text-[10px] font-black uppercase tracking-widest">Payload</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach($logs as $log)
                        <tr>
                            <td><span class="text-[10px] font-mono text-slate-400">#{{ $log->id }}</span></td>
                            <td>
                                <span class="badge bg-label-primary text-[9px] font-black uppercase tracking-tighter">{{ $log->event }}</span>
                            </td>
                            <td>
                                <span class="badge @if($log->status == 'processed') bg-label-success @elseif($log->status == 'failed') bg-label-danger @else bg-label-warning @endif text-[9px] font-black uppercase tracking-tighter">
                                    {{ $log->status }}
                                </span>
                                @if($log->error_message)
                                    <i class="ti ti-info-circle text-danger ms-1" data-bs-toggle="tooltip" title="{{ $log->error_message }}"></i>
                                @endif
                            </td>
                            <td><span class="text-[10px] font-bold text-slate-600">{{ $log->created_at->format('d/m/Y H:i:s') }}</span></td>
                            <td>
                                <button class="btn btn-xs btn-outline-primary font-black uppercase tracking-tighter" 
                                    data-bs-toggle="modal" data-bs-target="#modalPayload{{ $log->id }}">
                                    View JSON
                                </button>

                                <!-- Modal -->
                                <div class="modal fade" id="modalPayload{{ $log->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content glass-card p-4">
                                            <div class="modal-header border-0">
                                                <h5 class="modal-title font-black text-slate-900 uppercase tracking-tighter">Webhook Payload #{{ $log->id }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <pre class="bg-slate-900 text-emerald-400 p-6 rounded-2xl text-[11px] overflow-x-auto shadow-inner"><code>{{ json_encode($log->payload, JSON_PRETTY_PRINT) }}</code></pre>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-6 border-t bg-slate-50">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
