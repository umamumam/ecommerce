<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Services\BiteshipService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $biteship;

    public function __construct(BiteshipService $biteship)
    {
        $this->biteship = $biteship;
    }

    public function index(Request $request)
    {
        $query = Transaction::with('details.product', 'user')->latest();

        // Date Filter
        $startDate = $request->get('start_date', now()->subMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        if ($startDate && $endDate) {
            $query->whereDate('created_at', '>=', $startDate)
                  ->whereDate('created_at', '<=', $endDate);
        }

        // Status Filter
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(20);
        return view('admin.orders.index', compact('orders', 'startDate', 'endDate'));
    }

    public function show($id)
    {
        $order = Transaction::with('details.product', 'user')->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    public function bulkLabel(Request $request)
    {
        $ids = $request->ids;
        if (empty($ids)) {
            return response()->json(['message' => 'Pilih pesanan terlebih dahulu'], 400);
        }

        $transactions = Transaction::whereIn('id', $ids)->whereNotNull('biteship_order_id')->get();
        if ($transactions->isEmpty()) {
            return response()->json(['message' => 'Pesanan terpilih belum memiliki resi Biteship'], 400);
        }

        $orderIds = $transactions->pluck('biteship_order_id')->toArray();
        
        // Biteship supports bulk label by sending order_ids
        $response = $this->biteship->getBulkLabels($orderIds);

        if (isset($response['url'])) {
            return response()->json(['url' => $response['url']]);
        }

        return response()->json(['message' => 'Gagal mengambil label massal'], 500);
    }

    public function createShipment($id)
    {
        $order = Transaction::findOrFail($id);
        
        // Anti-fraud: Don't allow shipment for unpaid orders
        if ($order->status !== 'paid') {
            return back()->with('error', 'Pesanan ini belum dibayar. Tidak dapat membuat pengiriman.');
        }
        // Prepare data for Biteship
        $items = [];
        foreach($order->details as $detail) {
            $items[] = [
                'name' => $detail->product->name,
                'description' => "Order #{$order->code}",
                'value' => $detail->price,
                'weight' => $detail->product->weight,
                'quantity' => $detail->quantity
            ];
        }

        $originId = \App\Models\Setting::where('key', 'biteship_origin_id')->first()?->value ?? config('services.biteship.origin_id', 'IDNP3CL1044');
        $originLabel = \App\Models\Setting::where('key', 'biteship_origin_label')->first()?->value ?? 'Gudang Utama';

        $shipmentData = [
            'shipper_contact_name' => config('app.name'),
            'shipper_contact_phone' => '085799352991',
            'shipper_contact_email' => 'owner@store.com',
            'shipper_organization' => config('app.name'),
            'origin_contact_name' => $originLabel,
            'origin_contact_phone' => '085799352991',
            'origin_address' => 'Cluwak, Pati, Jawa Tengah',
            'origin_note' => 'Pintu Gerbang Hijau',
            'origin_postal_code' => 59157,
            'origin_area_id' => $originId,
            'destination_contact_name' => $order->shipping_name,
            'destination_contact_phone' => $order->shipping_phone,
            'destination_contact_email' => 'customer@mail.com',
            'destination_address' => $order->shipping_address,
            'destination_postal_code' => $order->shipping_postal_code ?? 12345,
            'destination_area_id' => $order->shipping_area_id,
            'courier_company' => strtolower($order->shipping_courier),
            'courier_type' => strtolower($order->shipping_service),
            'delivery_type' => 'now',
            'items' => $items
        ];

        $response = $this->biteship->createOrder($shipmentData);

        if (isset($response['id'])) {
            $order->update([
                'biteship_order_id' => $response['id'],
                'status' => 'processing',
                'shipping_waybill' => $response['courier']['waybill_id'] ?? null,
                'biteship_tracking_link' => $response['courier']['link'] ?? null
            ]);
            return back()->with('success', 'Berhasil membuat pengiriman di Biteship.');
        }

        return back()->with('error', 'Gagal membuat pengiriman: ' . ($response['error'] ?? 'Unknown Error'));
    }

    public function downloadLabel($id)
    {
        $order = Transaction::findOrFail($id);
        
        if (!$order->biteship_order_id) {
            return back()->with('error', 'Pesanan ini belum didaftarkan ke pengiriman.');
        }

        $response = $this->biteship->getLabel($order->biteship_order_id);

        \Log::info('Biteship Label Response Trace:', ['response' => $response]);

        if (isset($response['url'])) {
            return redirect($response['url']);
        }

        $message = $response['message'] ?? 'Link label belum tersedia.';
        if (str_contains($message, 'successfully')) {
            return back()->with('success', 'Resi sedang disiapkan oleh Biteship. Silakan klik tombol "CETAK RESI" lagi dalam 3 detik.');
        }

        return back()->with('error', 'Gagal mengambil label: ' . $message);
    }

    public function destroy($id)
    {
        $order = Transaction::findOrFail($id);
        $order->delete();

        return back()->with('success', 'Pesanan berhasil dihapus.');
    }
}
