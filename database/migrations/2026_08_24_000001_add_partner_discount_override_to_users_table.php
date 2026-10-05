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
            if (!Schema::hasColumn('users', 'partner_discount_override')) {
                // Per-customer discount override. When set, it takes precedence
                // over the global tier discount (e.g. a Wholesaler normally gets
                // 20% but this specific account can be given 25%).
                $table->decimal('partner_discount_override', 5, 2)->nullable()->after('vat_number');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'partner_discount_override')) {
                $table->dropColumn('partner_discount_override');
            }
        });
    }
};
