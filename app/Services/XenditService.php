<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class XenditService
{
    protected $secretKey;
    protected $baseUrl = 'https://api.xendit.co';

    public function __construct()
    {
        $this->secretKey = trim(config('services.xendit.key'));
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
                'payer_email' => $transaction->shipping_email ?? (Auth::user()->email ?? 'customer@mail.com'),
                'customer_details' => [
                    'given_names' => $transaction->shipping_name,
                    'mobile_number' => $transaction->shipping_phone ?? '+628123456789',
                    'email' => $transaction->shipping_email ?? (Auth::user()->email ?? 'customer@mail.com'),
                ],
                'items' => $items,
                'success_redirect_url' => route('checkout.success', $transaction->id),
                'failure_redirect_url' => route('checkout.index'),
                'currency' => 'IDR',
            ];

            Log::info('Xendit Create Invoice Payload (V2)', $payload);

            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . base64_encode($this->secretKey . ':')
            ])->post("{$this->baseUrl}/v2/invoices", $payload);

            if ($response->successful()) {
                return $response->json();
            }

            $errorBody = $response->json();
            $errorMessage = $errorBody['message'] ?? $response->body();
            
            Log::error('Xendit API Error: ' . $errorMessage);
            return ['error' => true, 'message' => $errorMessage];
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
