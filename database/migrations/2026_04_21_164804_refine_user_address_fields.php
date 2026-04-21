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
        Schema::table('users', function (Blueprint $table) {
            // Refine address fields
            $table->string('province')->nullable()->after('address');
            $table->string('city')->nullable()->after('province');
            $table->string('district')->nullable()->after('city');
            $table->string('postal_code', 10)->nullable()->after('district');
            
            // Helpful for online shop
            $table->string('username')->unique()->nullable()->after('name');
            $table->boolean('is_active')->default(true)->after('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['province', 'city', 'district', 'postal_code', 'username', 'is_active']);
        });
    }
};
