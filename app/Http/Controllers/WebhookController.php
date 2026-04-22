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
            // Find waybill ID and new status from payload
            $waybill_id = $payload['courier']['waybill_id'] ?? ($payload['waybill_id'] ?? null);
            $newStatus = $payload['status'] ?? null;

            if ($waybill_id && $newStatus) {
                // Map Biteship status to Transaction status if needed
                // For now, we update the transaction that matches the waybill
                $transaction = Transaction::where('waybill', $waybill_id)->first();

                if ($transaction) {
                    $updateData = ['status' => $newStatus];
                    
                    // If Biteship says 'delivered', we might want to mark as success
                    if ($newStatus === 'delivered') {
                        $updateData['status'] = 'success';
                    }

                    $transaction->update($updateData);

                    $log->update(['status' => 'processed']);
                } else {
                    $log->update([
                        'status' => 'processed',
                        'error_message' => "Transaction with waybill $waybill_id not found in our database"
                    ]);
                }
                return response()->json(['message' => 'ok'], 200);
            }

            $log->update(['status' => 'processed', 'error_message' => 'Missing waybill_id or status in payload']);
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
