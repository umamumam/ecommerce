<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\BiteshipService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class IntegrationController extends Controller
{
    protected $biteship;

    public function __construct(BiteshipService $biteship)
    {
        $this->biteship = $biteship;
    }

    public function index()
    {
        $locationsData = $this->biteship->getLocations();
        $locations = $locationsData['locations'] ?? [];
        
        $currentOriginId = \App\Models\Setting::where('key', 'biteship_origin_id')->first()?->value ?? env('BITESHIP_ORIGIN_ID');
        $currentOriginLabel = \App\Models\Setting::where('key', 'biteship_origin_label')->first()?->value;

        // API Info from DB or Env
        $biteshipKey = \App\Models\Setting::where('key', 'biteship_api_key')->first()?->value ?? env('BITESHIP_API_KEY');
        $biteshipWebhook = \App\Models\Setting::where('key', 'biteship_webhook_url')->first()?->value ?? env('BITESHIP_WEBHOOK_URL');
        $xenditKey = \App\Models\Setting::where('key', 'xendit_secret_key')->first()?->value ?? env('XENDIT_SECRET_KEY');
        $xenditToken = \App\Models\Setting::where('key', 'xendit_webhook_token')->first()?->value ?? env('XENDIT_CALLBACK_TOKEN');

        $accountInfo = [
            'name' => 'Premium Store',
            'status' => 'Active',
            'biteship_api_key' => $biteshipKey,
            'biteship_webhook' => $biteshipWebhook,
            'xendit_secret_key' => $xenditKey,
            'xendit_webhook_token' => $xenditToken,
            'api_key_masked' => substr($biteshipKey, 0, 15) . '...',
        ];

        return view('admin.integration.index', compact('locations', 'accountInfo', 'currentOriginId', 'currentOriginLabel'));
    }

    public function updateApiKeys(Request $request)
    {
        $request->validate([
            'biteship_api_key' => 'nullable',
            'biteship_webhook' => 'nullable',
            'xendit_secret_key' => 'nullable',
        ]);

        if ($request->has('biteship_api_key')) {
            \App\Models\Setting::updateOrCreate(['key' => 'biteship_api_key'], ['value' => $request->biteship_api_key]);
        }
        if ($request->has('biteship_webhook')) {
            \App\Models\Setting::updateOrCreate(['key' => 'biteship_webhook_url'], ['value' => $request->biteship_webhook]);
        }
        if ($request->has('xendit_secret_key')) {
            \App\Models\Setting::updateOrCreate(['key' => 'xendit_secret_key'], ['value' => $request->xendit_secret_key]);
        }
        if ($request->has('xendit_webhook_token')) {
            \App\Models\Setting::updateOrCreate(['key' => 'xendit_webhook_token'], ['value' => $request->xendit_webhook_token]);
        }

        return back()->with('success', 'Konfigurasi API berhasil diperbarui.');
    }

    public function updateOrigin(Request $request)
    {
        $request->validate([
            'origin_id' => 'required',
            'origin_label' => 'nullable',
        ]);

        \App\Models\Setting::updateOrCreate(
            ['key' => 'biteship_origin_id'],
            ['value' => $request->origin_id]
        );

        if ($request->origin_label) {
            \App\Models\Setting::updateOrCreate(
                ['key' => 'biteship_origin_label'],
                ['value' => $request->origin_label]
            );
        }
        
        return back()->with('success', 'Lokasi Asal (Origin) berhasil diperbarui.');
    }
}
