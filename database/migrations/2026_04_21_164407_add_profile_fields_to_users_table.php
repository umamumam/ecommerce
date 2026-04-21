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
        Schema::table('users', function (Blueprint $column) {
            $column->string('role')->default('user')->after('password');
            $column->string('phone')->nullable()->after('role');
            $column->text('address')->nullable()->after('phone');
            $column->string('profile_photo')->nullable()->after('address');
            $column->string('gender')->nullable()->after('profile_photo');
            $column->date('dob')->nullable()->after('gender');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $column) {
            $column->dropColumn(['role', 'phone', 'address', 'profile_photo', 'gender', 'dob']);
        });
    }
};
