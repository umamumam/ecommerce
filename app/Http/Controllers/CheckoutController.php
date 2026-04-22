<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Services\BiteshipService;
use App\Services\XenditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    protected $biteship;
    protected $xendit;

    public function __construct(BiteshipService $biteship, XenditService $xendit)
    {
        $this->biteship = $biteship;
        $this->xendit = $xendit;
    }

    /**
     * Show Checkout Page
     */
    public function index(Request $request)
    {
        $cart = session()->get('cart', []);
        
        // If coming from "Buy Now" but cart is empty, handle if needed.
        // For now, prioritize Cart.
        if (count($cart) == 0 && $request->product_id) {
            // Handle Buy Now by adding to cart first
            app(CartController::class)->add($request);
            $cart = session()->get('cart', []);
        }

        if (count($cart) == 0) {
            return redirect('/')->with('error', 'Keranjang belanja kosong');
        }

        $user = Auth::user();
        $initialAddress = $user->address;
        $initialPostal = $user->postal_code;
        $initialLocationName = $user->district ? "{$user->district}, {$user->city}" : $user->city;

        $totalPrice = 0;
        $totalWeight = 0;
        foreach($cart as $item) {
            $totalPrice += $item['price'] * $item['quantity'];
            $totalWeight += $item['weight'] * $item['quantity'];
        }

        return view('checkout.index', compact('cart', 'user', 'initialAddress', 'initialPostal', 'initialLocationName', 'totalPrice', 'totalWeight'));
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

        $originAreaId = \App\Models\Setting::where('key', 'biteship_origin_id')->first()?->value ?? env('BITESHIP_ORIGIN_ID', 'IDNP3CL1044'); 
        $rates = $this->biteship->getRates($originAreaId, $request->destination_area_id, $request->items);
        
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
            'shipping_area_id' => 'required',
            'shipping_address' => 'required',
            'shipping_courier' => 'required',
            'shipping_service' => 'required',
            'shipping_price' => 'required|integer',
        ]);

        $cart = session()->get('cart', []);
        if (count($cart) == 0) {
            return redirect('/')->with('error', 'Sesi belanja berakhir');
        }

        return DB::transaction(function () use ($request, $cart) {
            $totalPrice = 0;
            foreach($cart as $item) {
                $totalPrice += $item['price'] * $item['quantity'];
            }
            
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
                'shipping_name' => $request->shipping_name ?? Auth::user()->name,
                'shipping_phone' => $request->shipping_phone ?? (Auth::user()->phone ?? '08123456789'),
            ]);

            foreach($cart as $item) {
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['id'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'variant_1' => $item['variant_1'] ?? null,
                    'variant_2' => $item['variant_2'] ?? null,
                ]);

                // Reduce stock
                $product = Product::find($item['id']);
                if ($product) {
                    $product->decrement('stock', $item['quantity']);
                }
            }

            // Clear Cart
            session()->forget('cart');

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
