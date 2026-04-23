<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\WebhookLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    /**
     * Handle Biteship Webhook
     */
    public function handleBiteship(Request $request)
    {
        $payload = $request->all();

        if (empty($payload)) {
            return response()->json(['message' => 'ok'], 200);
        }

        $event = $request->header('X-Biteship-Event') ?? ($payload['event'] ?? 'unknown');

        // Save log
        $log = WebhookLog::create([
            'provider' => 'biteship',
            'event' => $event,
            'payload' => $payload,
            'status' => 'received'
        ]);

        try {
            $biteshipOrderId = $payload['order_id'] ?? null;
            $waybill_id = $payload['courier']['waybill_id'] ?? ($payload['waybill_id'] ?? null);
            $newStatus = isset($payload['status']) ? strtolower($payload['status']) : null;

            Log::info("Processing Biteship Webhook: OrderID: $biteshipOrderId, Waybill: $waybill_id, Status: $newStatus");

            if (($biteshipOrderId || $waybill_id) && $newStatus) {
                // Find transaction
                $query = Transaction::query();
                if ($biteshipOrderId) {
                    $query->where('biteship_order_id', $biteshipOrderId);
                } else {
                    $query->where('shipping_waybill', $waybill_id);
                }
                
                $transaction = $query->first();

                if ($transaction) {
                    $internalStatus = $this->mapBiteshipStatus($newStatus);
                    
                    Log::info("Mapping Biteship status '$newStatus' to internal '$internalStatus' for transaction #{$transaction->code}");

                    $updateData = ['status' => $internalStatus];
                    
                    if ($waybill_id && !$transaction->shipping_waybill) {
                        $updateData['shipping_waybill'] = $waybill_id;
                    }
                    
                    // Update tracking link if provided in webhook (some events include it)
                    if (isset($payload['courier']['link'])) {
                        $updateData['biteship_tracking_link'] = $payload['courier']['link'];
                    }

                    $transaction->update($updateData);

                    $log->update(['status' => 'processed']);
                    return response()->json(['message' => 'webhook processed'], 200);
                } else {
                    Log::warning("Transaction not found for Biteship Webhook. OrderID: $biteshipOrderId, Waybill: $waybill_id");
                    $log->update([
                        'status' => 'processed',
                        'error_message' => "Transaction not found for ID: $biteshipOrderId or Waybill: $waybill_id"
                    ]);
                }
            } else {
                $log->update(['status' => 'processed', 'error_message' => 'Missing order_id/waybill_id or status in payload']);
            }
            
            return response()->json(['message' => 'ok'], 200);
        } catch (\Exception $e) {
            $log->update([
                'status' => 'failed',
                'error_message' => $e->getMessage()
            ]);
            Log::error("Biteship Webhook Error: " . $e->getMessage());
            return response()->json(['message' => 'error handled'], 200);
        }
    }

    /**
     * Map Biteship status to local status
     */
    protected function mapBiteshipStatus($biteshipStatus)
    {
        $map = [
            'placed' => 'paid',
            'confirmed' => 'processing',
            'allocated' => 'processing',
            'picking_up' => 'processing',
            'picked_up' => 'shipping',
            'dropping_off' => 'shipping',
            'shipped' => 'shipping',
            'delivered' => 'completed',
            'cancelled' => 'cancelled',
            'rejected' => 'cancelled',
        ];

        return $map[$biteshipStatus] ?? 'processing';
    }
    /**
     * Handle Xendit Webhook
     */
    public function handleXendit(Request $request)
    {
        // Validate Callback Token
        $callbackToken = $request->header('x-callback-token');
        $storedToken = config('services.xendit.callback_token');

        Log::info('Xendit Webhook received', [
            'header_token' => $callbackToken,
            'stored_token' => $storedToken,
            'payload' => $request->all()
        ]);

        if ($storedToken && $callbackToken !== $storedToken) {
            Log::warning("Xendit Webhook called with invalid token.");
            return response()->json(['message' => 'invalid token'], 403);
        }

        $payload = $request->all();
        
        // Save log
        $log = WebhookLog::create([
            'provider' => 'xendit',
            'event' => 'invoice.paid',
            'payload' => $payload,
            'status' => 'received'
        ]);

        try {
            $externalId = $payload['external_id'] ?? null;
            $status = $payload['status'] ?? null;

            if ($externalId && $status === 'PAID') {
                $transaction = Transaction::where('code', $externalId)->first();
                if ($transaction) {
                    Log::info('Transaction found, updating to PAID', ['code' => $externalId]);
                    $transaction->update(['status' => 'paid']);
                    $log->update(['status' => 'processed']);
                    
                    Log::info("Transaction $externalId marked as PAID via Xendit Webhook");
                    return response()->json(['message' => 'ok'], 200);
                }
            }

            $log->update(['status' => 'processed', 'error_message' => 'Order not found or status not PAID']);
            return response()->json(['message' => 'ok'], 200);
        } catch (\Exception $e) {
            $log->update(['status' => 'failed', 'error_message' => $e->getMessage()]);
            return response()->json(['message' => 'error'], 200);
        }
    }
}
