<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Services\BiteshipService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    protected $biteship;
    protected $xendit;

    public function __construct(BiteshipService $biteship, \App\Services\XenditService $xendit)
    {
        $this->biteship = $biteship;
        $this->xendit = $xendit;
    }

    /**
     * Show Checkout Page
     */
    public function index(Request $request)
    {
        if (!$request->product_id) {
            return redirect('/');
        }
        $product = Product::findOrFail($request->product_id);
        $qty = $request->qty ?? 1;
        $variant1 = $request->variant_1;
        $variant2 = $request->variant_2;
        $user = Auth::user();

        // Prepare initial address string
        $initialAddress = $user->address;
        $initialPostal = $user->postal_code;
        $initialLocationName = $user->district ? "{$user->district}, {$user->city}" : $user->city;

        return view('checkout.index', compact('product', 'qty', 'variant1', 'variant2', 'user', 'initialAddress', 'initialPostal', 'initialLocationName'));
    }

    /**
     * Get Shipping Rates (AJAX)
     */
    public function getShippingRates(Request $request)
    {
        $request->validate([
            'destination_area_id' => 'required',
            'items' => 'required|array',
        ]);

        // Origin Area ID - Recommended: Make this configurable in settings
        // Current example: Jakarta Selatan ID
        $originAreaId = env('BITESHIP_ORIGIN_ID', 'IDNP3CL1044'); 

        $rates = $this->biteship->getRates($originAreaId, $request->destination_area_id, $request->items);
        
        Log::info('Biteship Rates Response:', (array)$rates);

        return response()->json($rates);
    }

    /**
     * Search Area (AJAX)
     */
    public function searchArea(Request $request)
    {
        $areas = $this->biteship->searchArea($request->q);
        return response()->json($areas);
    }

    /**
     * Process Checkout
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty' => 'required|integer|min:1',
            'shipping_area_id' => 'required',
            'shipping_address' => 'required',
            'shipping_courier' => 'required',
            'shipping_service' => 'required',
            'shipping_price' => 'required|integer',
            'shipping_postal_code' => 'nullable'
        ]);

        return DB::transaction(function () use ($request) {
            $product = Product::find($request->product_id);
            $totalPrice = $product->price * $request->qty;
            
            $transaction = Transaction::create([
                'user_id' => Auth::id(),
                'code' => 'INV/' . date('Ymd') . '/' . strtoupper(Str::random(5)),
                'total_price' => $totalPrice,
                'shipping_price' => $request->shipping_price,
                'grand_total' => $totalPrice + $request->shipping_price,
                'status' => 'pending',
                'shipping_courier' => $request->shipping_courier,
                'shipping_service' => $request->shipping_service,
                'shipping_area_id' => $request->shipping_area_id,
                'shipping_address' => $request->shipping_address,
                'shipping_postal_code' => $request->shipping_postal_code,
                'shipping_name' => Auth::user()->name,
                'shipping_phone' => Auth::user()->phone ?? '08123456789',
            ]);

            TransactionDetail::create([
                'transaction_id' => $transaction->id,
                'product_id' => $product->id,
                'price' => $product->price,
                'quantity' => $request->qty,
                'variant_1' => $request->variant_1,
                'variant_2' => $request->variant_2,
            ]);

            // Create Xendit Invoice
            $invoice = $this->xendit->createInvoice($transaction);
            if ($invoice && isset($invoice['invoice_url'])) {
                $transaction->update([
                    'payment_link' => $invoice['invoice_url']
                ]);
            }

            return redirect()->route('checkout.success', $transaction->id);
        });
    }

    public function success($id)
    {
        $transaction = Transaction::with('details.product')->findOrFail($id);
        return view('checkout.success', compact('transaction'));
    }
}
