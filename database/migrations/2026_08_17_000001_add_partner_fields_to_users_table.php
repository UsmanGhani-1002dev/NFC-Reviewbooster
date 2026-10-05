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
            $table->string('partner_type')->default('standard')->after('role');
            $table->string('partner_status')->nullable()->after('partner_type'); // 'pending', 'approved', 'rejected'
            $table->string('vat_number')->nullable()->after('partner_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['partner_type', 'partner_status', 'vat_number']);
        });
    }
};
