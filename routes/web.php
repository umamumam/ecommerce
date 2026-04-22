<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CheckoutController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $categories = \App\Models\Category::where('is_active', true)->get();
    $products = \App\Models\Product::with('category')->where('is_active', true)->latest()->take(10)->get();
    return view('welcome', compact('categories', 'products'));
});

Route::get('/product/{slug}', [ProductController::class, 'userShow'])->name('product.detail');

Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
    
    // Order Management
    Route::get('/orders', [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('admin.orders.index');
    Route::delete('/orders/{id}', [\App\Http\Controllers\Admin\OrderController::class, 'destroy'])->name('admin.orders.destroy');
    Route::get('/orders/{id}', [\App\Http\Controllers\Admin\OrderController::class, 'show'])->name('admin.orders.show');
    Route::post('/orders/{id}/shipment', [\App\Http\Controllers\Admin\OrderController::class, 'createShipment'])->name('admin.orders.shipment');
    Route::get('/orders/{id}/label', [\App\Http\Controllers\Admin\OrderController::class, 'downloadLabel'])->name('admin.orders.label');
    Route::post('/orders/bulk-label', [\App\Http\Controllers\Admin\OrderController::class, 'bulkLabel'])->name('admin.orders.bulkLabel');

    // Logistics Tools
    Route::get('/tools/ongkir', [\App\Http\Controllers\Admin\ToolController::class, 'indexOngkir'])->name('admin.tools.ongkir');
    Route::get('/tools/resi', [\App\Http\Controllers\Admin\ToolController::class, 'indexResi'])->name('admin.tools.resi');
    Route::get('/tools/scan', [\App\Http\Controllers\Admin\ToolController::class, 'indexScan'])->name('admin.tools.scan');
    Route::post('/tools/scan', [\App\Http\Controllers\Admin\ToolController::class, 'postScan']);
    Route::get('/tools/webhooks', [\App\Http\Controllers\Admin\ToolController::class, 'indexWebhooks'])->name('admin.tools.webhooks');
    Route::post('/tools/rates', [\App\Http\Controllers\Admin\ToolController::class, 'checkRates']);
    Route::get('/tools/track/{waybill}', [\App\Http\Controllers\Admin\ToolController::class, 'checkWaybill']);

    Route::get('/integration', [\App\Http\Controllers\Admin\IntegrationController::class, 'index'])->name('admin.integration.index');
    Route::post('/integration/origin', [\App\Http\Controllers\Admin\IntegrationController::class, 'updateOrigin'])->name('admin.integration.updateOrigin');
    Route::post('/integration/keys', [\App\Http\Controllers\Admin\IntegrationController::class, 'updateApiKeys'])->name('admin.integration.updateKeys');

    // Xendit
    Route::get('/xendit', [\App\Http\Controllers\Admin\XenditController::class, 'index'])->name('admin.xendit.index');

    // Courier Management
    Route::get('/couriers', [\App\Http\Controllers\Admin\CourierController::class, 'index'])->name('admin.couriers.index');
    Route::post('/couriers', [\App\Http\Controllers\Admin\CourierController::class, 'store'])->name('admin.couriers.store');
    Route::post('/couriers/{id}/toggle', [\App\Http\Controllers\Admin\CourierController::class, 'toggle']);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/email/verification-status', function () {
        return response()->json([
            'verified' => auth()->user()->hasVerifiedEmail(),
        ]);
    })->name('verification.status');

    Route::get('/account', [\App\Http\Controllers\AccountController::class, 'index'])->name('account');
    Route::get('/account/orders', [\App\Http\Controllers\AccountController::class, 'orders'])->name('account.orders');
    Route::get('/account/orders/{id}', [\App\Http\Controllers\AccountController::class, 'orderShow'])->name('account.orders.show');
    Route::get('/account/profile', [\App\Http\Controllers\AccountController::class, 'profile'])->name('account.profile');
    Route::post('/account/profile', [\App\Http\Controllers\AccountController::class, 'updateProfile'])->name('account.profile.update');
    Route::get('/account/address', [\App\Http\Controllers\AccountController::class, 'address'])->name('account.address');
    Route::post('/account/address', [\App\Http\Controllers\AccountController::class, 'updateAddress'])->name('account.address.update');

    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/success/{id}', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::post('/shipping/rates', [CheckoutController::class, 'getShippingRates']);
    Route::get('/shipping/areas', [CheckoutController::class, 'searchArea']);

    Route::get('/account/transactions/{id}', function ($id) {
        $transaction = \App\Models\Transaction::with('details.product')->where('user_id', auth()->id())->findOrFail($id);
        return view('account.transactions.show', compact('transaction'));
    })->name('account.transactions.show');
});

// Guest Friendly Cart Routes
Route::get('/cart', [\App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [\App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/update', [\App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [\App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');

Route::post('/webhook/biteship', [\App\Http\Controllers\Api\BiteshipWebhookController::class, 'handle']);
Route::post('/webhook/xendit', [\App\Http\Controllers\WebhookController::class, 'handleXendit']);

require __DIR__ . '/auth.php';
