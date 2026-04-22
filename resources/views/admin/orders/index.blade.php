<x-app-layout>
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Manajemen /</span> Order</h4>

        <div class="card overflow-hidden">
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3 text-[10px] font-black uppercase tracking-widest text-slate-500">Order & Customer</th>
                            <th class="py-3 text-[10px] font-black uppercase tracking-widest text-slate-500">Produk</th>
                            <th class="py-3 text-[10px] font-black uppercase tracking-widest text-slate-500">Total</th>
                            <th class="py-3 text-[10px] font-black uppercase tracking-widest text-slate-500">Ekspedisi</th>
                            <th class="py-3 text-[10px] font-black uppercase tracking-widest text-slate-500">Status</th>
                            <th class="py-3 text-[10px] font-black uppercase tracking-widest text-slate-500 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse($orders as $order)
                        <tr>
                            <td class="ps-4 py-4">
                                <div class="d-flex flex-column">
                                    <span class="text-xs font-black text-slate-900 leading-none mb-1">{{ $order->code }}</span>
                                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter">{{ $order->user->name }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @php $firstDetail = $order->details->first(); @endphp
                                    @if($firstDetail)
                                    <div class="w-10 h-10 rounded border p-1 bg-white me-2">
                                        <img src="{{ $firstDetail->product->image ? (Str::startsWith($firstDetail->product->image, 'http') ? $firstDetail->product->image : asset('storage/'.$firstDetail->product->image)) : asset('assets/img/elements/1.jpg') }}" class="w-full h-full object-cover rounded">
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="text-[11px] font-bold text-slate-700 line-clamp-1" style="max-width: 140px;">{{ $firstDetail->product->name }}</span>
                                        @if($order->details->count() > 1)
                                        <span class="text-[9px] text-primary font-black uppercase">+{{ $order->details->count() - 1 }} Item Lainnya</span>
                                        @endif
                                    </div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="text-xs font-black text-slate-800">Rp{{ number_format($order->grand_total, 0, ',', '.') }}</div>
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="text-[10px] font-bold text-slate-600 uppercase">{{ $order->shipping_courier }}</span>
                                    <span class="text-[9px] text-slate-400 font-medium uppercase tracking-tight">{{ $order->shipping_service }}</span>
                                </div>
                            </td>
                            <td>
                                @php
                                    $class = match($order->status) {
                                        'pending' => 'bg-label-warning',
                                        'paid' => 'bg-label-success',
                                        'processing' => 'bg-label-info',
                                        'shipping' => 'bg-label-primary',
                                        'completed' => 'bg-label-emerald',
                                        default => 'bg-label-secondary'
                                    };
                                @endphp
                                <span class="badge {{ $class }} text-[9px] font-black uppercase px-2 py-1.5 rounded">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td class="text-center pe-4">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-icon btn-sm btn-label-primary shadow-none">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                    <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Hapus pesanan ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-icon btn-sm btn-label-danger shadow-none">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="py-5 text-center">
                                    <i class="ti ti-receipt-off display-2 text-slate-200"></i>
                                    <h6 class="mt-3 text-slate-400">Belum ada pesanan yang masuk</h6>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
