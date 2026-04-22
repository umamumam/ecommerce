<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class XenditService
{
    protected $secretKey;
    protected $baseUrl = 'https://api.xendit.co';

    public function __construct()
    {
        $this->secretKey = config('services.xendit.key');
    }

    /**
     * Create Invoice
     */
    public function createInvoice($transaction)
    {
        try {
            // Prepare items
            $items = [];
            foreach ($transaction->details as $detail) {
                $items[] = [
                    'name' => $detail->product->name,
                    'quantity' => $detail->quantity,
                    'price' => (int) $detail->price,
                    'category' => 'E-commerce',
                ];
            }

            // Add shipping as an item
            if ($transaction->shipping_price > 0) {
                $items[] = [
                    'name' => 'Shipping: ' . strtoupper($transaction->shipping_courier) . ' ' . $transaction->shipping_service,
                    'quantity' => 1,
                    'price' => (int) $transaction->shipping_price,
                    'category' => 'Shipping',
                ];
            }

            $payload = [
                'external_id' => $transaction->code,
                'amount' => (int) $transaction->grand_total,
                'description' => 'Pembayaran Pesanan #' . $transaction->code,
                'customer' => [
                    'given_names' => $transaction->shipping_name,
                    'mobile_number' => $transaction->shipping_phone ?? '+628123456789',
                ],
                'items' => $items,
                'success_redirect_url' => route('checkout.success', $transaction->id),
                'failure_redirect_url' => route('checkout.index'),
                'currency' => 'IDR',
            ];

            Log::info('Xendit Create Invoice Payload', $payload);

            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . base64_encode($this->secretKey . ':')
            ])->post("{$this->baseUrl}/v1/invoices", $payload);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Xendit API Error: ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('Xendit Service Error: ' . $e->getMessage());
            return null;
        }
    }
    /**
     * Get Balance
     */
    public function getBalance()
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . base64_encode($this->secretKey . ':')
            ])->get("{$this->baseUrl}/balance", [
                'account_type' => 'CASH'
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Xendit Get Balance Error: ' . $e->getMessage());
            return null;
        }
    }
}
