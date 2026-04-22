<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('code')->unique(); // Format: INV/YYYYMMDD/XXXXX
            $table->integer('total_price');
            $table->integer('shipping_price')->default(0);
            $table->integer('grand_total');
            $table->string('status')->default('pending'); // pending, paid, process, shipping, completed, cancelled
            
            // Shipping Info (Biteship specific)
            $table->string('shipping_courier')->nullable();
            $table->string('shipping_service')->nullable();
            $table->string('shipping_waybill')->nullable(); // Resi
            $table->string('shipping_area_id')->nullable(); // Biteship Area ID
            $table->text('shipping_address')->nullable();
            $table->string('shipping_name')->nullable();
            $table->string('shipping_phone')->nullable();
            
            // Biteship Order ID for Webhook tracking
            $table->string('biteship_order_id')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
