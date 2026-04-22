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

    public function index()
    {
        $orders = Transaction::with('details.product', 'user')->latest()->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Transaction::with('details.product', 'user')->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    public function createShipment($id)
    {
        $order = Transaction::findOrFail($id);
        
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

        $shipmentData = [
            'shipper_contact_name' => 'Owner Store',
            'shipper_contact_phone' => '08123456789',
            'shipper_contact_email' => 'owner@store.com',
            'shipper_organization' => 'Toko Kita',
            'origin_contact_name' => 'Gudang Utama',
            'origin_contact_phone' => '08123456789',
            'origin_address' => 'Jl. Fashion Perkasa No. 10',
            'origin_note' => 'Pintu Gerbang Hijau',
            'origin_postal_code' => 12110,
            'origin_area_id' => env('BITESHIP_ORIGIN_ID', 'IDNP3CL1044'),
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
                'waybill' => $response['courier']['waybill_id'] ?? null
            ]);
            return back()->with('success', 'Berhasil membuat pengiriman di Biteship.');
        }

        return back()->with('error', 'Gagal membuat pengiriman: ' . ($response['error'] ?? 'Unknown Error'));
    }
}
