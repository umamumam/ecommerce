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
        
        if (isset($rates['pricing']) && is_array($rates['pricing'])) {
            $rates['pricing'] = array_map(function ($item) {
                return $this->enrichRateItem($item);
            }, $rates['pricing']);
        }
        
        return response()->json($rates);
    }

    /**
     * Enrich rate item with local logo, formatted text, and service category
     */
    private function enrichRateItem(array $item): array
    {
        $code = strtolower($item['courier_code'] ?? ($item['company'] ?? 'default'));
        $serviceCode = strtolower($item['courier_service_code'] ?? '');
        $serviceName = $item['courier_service_name'] ?? strtoupper($serviceCode);
        $type = strtolower($item['type'] ?? ($item['service_type'] ?? 'standard'));

        // Map courier display names
        $courierNames = [
            'jne' => 'JNE Express',
            'jnt' => 'J&T Express',
            'j&t' => 'J&T Express',
            'sicepat' => 'SiCepat Ekspres',
            'anteraja' => 'AnterAja',
            'ninja' => 'Ninja Xpress',
            'lion' => 'Lion Parcel',
            'pos' => 'POS Indonesia',
            'tiki' => 'TIKI',
            'wahana' => 'Wahana Express',
            'ide' => 'IDexpress',
            'idexpress' => 'IDexpress',
            'sap' => 'SAP Express',
            'sentral' => 'Sentral Cargo',
            'rpx' => 'RPX Logistics',
            'paxel' => 'Paxel',
            'gosend' => 'GoSend',
            'grab' => 'GrabExpress',
            'spx' => 'SPX Express',
            'lalamove' => 'Lalamove',
            'deliveree' => 'Deliveree',
        ];

        $courierName = $item['courier_name'] ?? ($courierNames[$code] ?? strtoupper($code));

        // Official Biteship WebP Logo path (fallback to SVG or default)
        if (file_exists(public_path("assets/img/couriers/{$code}.webp"))) {
            $logoFilename = "{$code}.webp";
        } elseif (file_exists(public_path("assets/img/couriers/{$code}.svg"))) {
            $logoFilename = "{$code}.svg";
        } else {
            $logoFilename = 'default.svg';
        }
        $item['courier_logo'] = asset("assets/img/couriers/{$logoFilename}");
        $item['courier_name'] = $courierName;

        // Categorize Service
        $isExpress = str_contains($serviceCode, 'yes') || 
                     str_contains($serviceCode, 'ons') || 
                     str_contains($serviceCode, 'best') || 
                     str_contains($serviceCode, 'next_day') || 
                     str_contains(strtolower($serviceName), 'esok') || 
                     str_contains(strtolower($serviceName), 'express') || 
                     $type === 'overnight' || $type === 'express';

        $isCargo = str_contains($serviceCode, 'cargo') || 
                   str_contains($serviceCode, 'truck') || 
                   str_contains($serviceCode, 'jtr') || 
                   str_contains($serviceCode, 'gokil') || 
                   str_contains(strtolower($serviceName), 'kargo') || 
                   str_contains(strtolower($serviceName), 'cargo') || 
                   $type === 'cargo' || $type === 'trucking';

        $isInstant = str_contains($serviceCode, 'instant') || 
                     str_contains($serviceCode, 'sameday') || 
                     str_contains($serviceCode, 'same_day') || 
                     str_contains(strtolower($serviceName), 'instant') || 
                     str_contains(strtolower($serviceName), 'same day') || 
                     $type === 'instant' || $type === 'same_day';

        $isEconomy = str_contains($serviceCode, 'eco') || 
                     str_contains($serviceCode, 'oke') || 
                     str_contains($serviceCode, 'hemat') || 
                     str_contains(strtolower($serviceName), 'hemat') || 
                     str_contains(strtolower($serviceName), 'ekonomi') || 
                     $type === 'economy';

        if ($isInstant) {
            $item['category'] = 'instant';
            $item['category_label'] = 'Instant / Same Day';
        } elseif ($isExpress) {
            $item['category'] = 'express';
            $item['category_label'] = 'Express / 1 Hari';
        } elseif ($isCargo) {
            $item['category'] = 'cargo';
            $item['category_label'] = 'Kargo / Trucking';
        } elseif ($isEconomy) {
            $item['category'] = 'economy';
            $item['category_label'] = 'Ekonomi / Hemat';
        } else {
            $item['category'] = 'regular';
            $item['category_label'] = 'Reguler Standar';
        }

        // Formatted Duration in Indonesian
        $rawDuration = $item['duration'] ?? '';
        $cleanDuration = str_ireplace(['days', 'day', 'hari'], ['Hari', 'Hari', 'Hari'], $rawDuration);
        $cleanDuration = str_ireplace(['hours', 'hour', 'jam'], ['Jam', 'Jam', 'Jam'], $cleanDuration);
        $item['formatted_duration'] = trim($cleanDuration) ?: '1 - 3 Hari';

        // Formatted Price
        $price = (int) ($item['price'] ?? 0);
        $item['formatted_price'] = 'Rp ' . number_format($price, 0, ',', '.');

        // Service Description fallback
        if (empty($item['description'])) {
            if ($isExpress) {
                $item['description'] = 'Layanan kilat prioritas sampai tujuan lebih cepat.';
            } elseif ($isCargo) {
                $item['description'] = 'Layanan pengiriman paket besar/berat dengan ongkir hemat.';
            } elseif ($isInstant) {
                $item['description'] = 'Pengiriman kilat kurir langsung tiba dalam hitungan jam.';
            } elseif ($isEconomy) {
                $item['description'] = 'Pilihan ekonomis dengan tarif super terjangkau.';
            } else {
                $item['description'] = 'Pengiriman reguler dengan jangkauan luas ke seluruh Indonesia.';
            }
        }

        return $item;
    }

    public function checkWaybill($waybill, Request $request)
    {
        $waybill = trim($waybill);
        $tracking = $this->biteship->trackOrder($waybill, $request->courier);
        return response()->json($tracking);
    }
}
