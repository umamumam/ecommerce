<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BiteshipWebhookController extends Controller
{
    /**
     * Handle Webhook from Biteship
     */
    public function handle(Request $request)
    {
        Log::info('Biteship Webhook Received', $request->all());

        $event = $request->input('event');
        $biteshipOrderId = $request->input('order_id');
        $status = $request->input('status');
        $waybillId = $request->input('waybill_id');

        // Find transaction by Biteship Order ID
        $transaction = Transaction::where('biteship_order_id', $biteshipOrderId)->first();

        if (!$transaction) {
            // Find by code if order_id mapping is missing (fallback)
            // But usually we map them on creation
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        // Map Biteship status to our internal status
        $internalStatus = $this->mapStatus($status);

        $updateData = ['status' => $internalStatus];
        
        if ($waybillId) {
            $updateData['shipping_waybill'] = $waybillId;
        }

        $transaction->update($updateData);

        return response()->json(['message' => 'Webhook processed successfully']);
    }

    /**
     * Map Biteship status to local status
     */
    protected function mapStatus($biteshipStatus)
    {
        $map = [
            'placed' => 'paid',
            'confirmed' => 'process',
            'allocated' => 'process',
            'picking_up' => 'process',
            'picked_up' => 'shipping',
            'dropping_off' => 'shipping',
            'shipped' => 'shipping',
            'delivered' => 'completed',
            'cancelled' => 'cancelled',
            'rejected' => 'cancelled',
        ];

        return $map[$biteshipStatus] ?? 'process';
    }
}
