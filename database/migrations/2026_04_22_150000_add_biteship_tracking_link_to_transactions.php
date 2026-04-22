<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $col) {
            $col->string('biteship_tracking_link')->nullable()->after('shipping_waybill');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $col) {
            $col->dropColumn('biteship_tracking_link');
        });
    }
};
