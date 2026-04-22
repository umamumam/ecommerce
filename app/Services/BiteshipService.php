<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BiteshipService
{
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = env('BITESHIP_API_KEY');
        $this->baseUrl = 'https://api.biteship.com';
    }

    /**
     * Search for area/location ID by query
     */
    public function searchArea($query)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => $this->apiKey,
            ])->get("{$this->baseUrl}/v1/maps/areas", [
                'countries' => 'ID',
                'input' => $query,
                'type' => 'single',
            ]);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Biteship searchArea Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get shipping rates
     */
    public function getRates($originAreaId, $destinationAreaId, $items)
    {
        try {
            $activeCouriers = \App\Models\Courier::where('is_active', true)->pluck('code')->toArray();
            $courierCodes = count($activeCouriers) > 0 ? implode(',', $activeCouriers) : 'jne,jnt,sicepat';

            $payload = [
                'origin_area_id' => $originAreaId,
                'destination_area_id' => $destinationAreaId,
                'couriers' => $courierCodes,
                'items' => $items,
            ];

            Log::info('Biteship getRates Request', [
                'url' => "{$this->baseUrl}/v1/rates/couriers",
                'api_key' => substr($this->apiKey, 0, 20) . '...',
                'payload' => $payload
            ]);

            $response = Http::withHeaders([
                'Authorization' => $this->apiKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->post("{$this->baseUrl}/v1/rates/couriers", $payload);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Biteship getRates Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Create order/shipment
     */
    public function createOrder($data)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => $this->apiKey,
            ])->post("{$this->baseUrl}/v1/orders", $data);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Biteship createOrder Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Track shipment
     */
    public function trackOrder($waybillId, $courierCode = null)
    {
        try {
            $url = $courierCode 
                ? "{$this->baseUrl}/v1/trackings/{$waybillId}/couriers/{$courierCode}"
                : "{$this->baseUrl}/v1/trackings/{$waybillId}";

            $response = Http::withHeaders([
                'Authorization' => $this->apiKey,
            ])->get($url);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Biteship trackOrder Error: ' . $e->getMessage());
            return null;
        }
    }
    /**
     * Get saved locations from Biteship
     */
    public function getLocations()
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => $this->apiKey,
            ])->get("{$this->baseUrl}/v1/locations");

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Biteship getLocations Error: ' . $e->getMessage());
            return null;
        }
    }
}
