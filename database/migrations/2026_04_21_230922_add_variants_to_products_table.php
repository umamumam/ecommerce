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
        Schema::table('products', function (Blueprint $table) {
            $table->string('variant_1_name')->nullable()->after('description');
            $table->json('variant_1_options')->nullable()->after('variant_1_name');
            $table->string('variant_2_name')->nullable()->after('variant_1_options');
            $table->json('variant_2_options')->nullable()->after('variant_2_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['variant_1_name', 'variant_1_options', 'variant_2_name', 'variant_2_options']);
        });
    }
};
