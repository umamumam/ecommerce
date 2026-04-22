<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('cart.index', compact('cart'));
    }

    public function add(Request $request)
    {
        $product = Product::findOrFail($request->product_id);
        $cart = session()->get('cart', []);

        $id = $request->product_id . ($request->variant_1 ?? '') . ($request->variant_2 ?? '');

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += $request->qty ?? 1;
        } else {
            $cart[$id] = [
                "id" => $product->id,
                "name" => $product->name,
                "quantity" => $request->qty ?? 1,
                "price" => $product->price,
                "weight" => $product->weight,
                "image" => is_array($product->images) && count($product->images) > 0 ? $product->images[0] : null,
                "variant_1" => $request->variant_1,
                "variant_2" => $request->variant_2,
            ];
        }

        session()->put('cart', $cart);
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'cart_count' => count($cart),
                'message' => 'Produk berhasil ditambahkan ke keranjang'
            ]);
        }

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang');
    }

    public function update(Request $request)
    {
        if ($request->id && $request->quantity) {
            $cart = session()->get('cart');
            $cart[$request->id]["quantity"] = $request->quantity;
            session()->put('cart', $cart);
            return response()->json(['success' => true]);
        }
    }

    public function remove(Request $request)
    {
        if ($request->id) {
            $cart = session()->get('cart');
            if (isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }
            return response()->json(['success' => true]);
        }
    }
}
