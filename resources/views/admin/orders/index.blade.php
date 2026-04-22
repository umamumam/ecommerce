<x-app-layout>
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Manajemen /</span> Order</h4>

        <div class="card overflow-hidden">
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th class="text-[10px] font-black uppercase tracking-widest">Order ID</th>
                            <th class="text-[10px] font-black uppercase tracking-widest">Pelanggan</th>
                            <th class="text-[10px] font-black uppercase tracking-widest">Total</th>
                            <th class="text-[10px] font-black uppercase tracking-widest">Kurir</th>
                            <th class="text-[10px] font-black uppercase tracking-widest">Status</th>
                            <th class="text-[10px] font-black uppercase tracking-widest text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach($orders as $order)
                        <tr>
                            <td>
                                <div class="flex flex-col">
                                    <span class="text-xs font-black text-slate-900">{{ $order->code }}</span>
                                    <span class="text-[9px] text-slate-400">{{ $order->created_at->format('d M Y, H:i') }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center font-black text-[10px]">{{ substr($order->shipping_name, 0, 1) }}</div>
                                    <div class="flex flex-col">
                                        <span class="text-xs font-bold">{{ $order->shipping_name }}</span>
                                        <span class="text-[10px] text-slate-400">{{ $order->shipping_phone }}</span>
                                    </div>
                                </div>
                            </td>
                            <td><span class="text-xs font-black">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span></td>
                            <td>
                                <div class="flex flex-col">
                                    <span class="text-[10px] font-black uppercase">{{ $order->shipping_courier }}</span>
                                    <span class="text-[9px] text-slate-400">{{ $order->shipping_service }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="badge @if($order->status == 'pending') bg-label-warning @elseif($order->status == 'paid') bg-label-success @elseif($order->status == 'processing') bg-label-primary @else bg-label-secondary @endif text-[9px] uppercase font-black">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="flex items-center justify-center gap-2">
                                    @if($order->status == 'paid' && !$order->biteship_order_id)
                                    <form action="{{ route('admin.orders.shipment', $order->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-xs btn-primary font-black uppercase tracking-tighter">Request Pickup</button>
                                    </form>
                                    @endif
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-xs btn-outline-secondary">Detail</a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
