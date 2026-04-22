<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\BiteshipService;
use Illuminate\Http\Request;

class ToolController extends Controller
{
    protected $biteship;

    public function __construct(BiteshipService $biteship)
    {
        $this->biteship = $biteship;
    }

    public function indexOngkir()
    {
        return view('admin.tools.ongkir');
    }

    public function indexResi()
    {
        return view('admin.tools.resi');
    }

    public function indexScan()
    {
        return view('admin.tools.scan');
    }

    public function postScan(Request $request)
    {
        $request->validate(['waybill' => 'required']);
        
        $transaction = \App\Models\Transaction::where('waybill', $request->waybill)->first();
        
        if ($transaction) {
            if ($transaction->status === 'success') {
                return back()->with('error', "Resi {$request->waybill}: SUDAH DELIVERED!");
            }
            $transaction->update(['status' => 'shipping']);
            return back()->with('success', "Resi {$request->waybill} berhasil di Scan Out!");
        }
        
        return back()->with('error', "Resi {$request->waybill} tidak ditemukan.");
    }

    public function indexWebhooks()
    {
        $logs = \App\Models\WebhookLog::latest()->paginate(20);
        return view('admin.tools.webhooks', compact('logs'));
    }

    public function checkRates(Request $request)
    {
        $request->validate([
            'origin' => 'required',
            'destination' => 'required',
            'weight' => 'required|integer'
        ]);

        $items = [[
            'name' => 'Paket Manual',
            'description' => 'Cek ongkir manual',
            'value' => 50000,
            'weight' => (int) $request->weight,
            'quantity' => 1,
            'length' => 10,
            'width' => 10,
            'height' => 10
        ]];

        $rates = $this->biteship->getRates($request->origin, $request->destination, $items);
        
        return response()->json($rates);
    }

    public function checkWaybill($waybill, Request $request)
    {
        $waybill = trim($waybill);
        $tracking = $this->biteship->trackOrder($waybill, $request->courier);
        return response()->json($tracking);
    }
}
