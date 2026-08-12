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
        Schema::create('reseller_transaction_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reseller_transaction_id')->nullable();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('price');
            $table->integer('quantity');
            $table->string('variant_1')->nullable();
            $table->string('variant_2')->nullable();
            $table->integer('subtotal')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reseller_transaction_details');
    }
};
