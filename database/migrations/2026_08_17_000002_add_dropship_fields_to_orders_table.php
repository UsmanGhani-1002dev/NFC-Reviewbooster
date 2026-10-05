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
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'user_id')) {
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete()->after('id');
            }
            if (!Schema::hasColumn('orders', 'is_dropship')) {
                $table->boolean('is_dropship')->default(false)->after('stripe_payment_intent_id');
            }
            if (!Schema::hasColumn('orders', 'partner_role')) {
                $table->string('partner_role')->nullable()->after('is_dropship');
            }
            if (!Schema::hasColumn('orders', 'billing_details')) {
                $table->json('billing_details')->nullable()->after('shipping_address');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $colsToDrop = [];
            if (Schema::hasColumn('orders', 'is_dropship')) $colsToDrop[] = 'is_dropship';
            if (Schema::hasColumn('orders', 'partner_role')) $colsToDrop[] = 'partner_role';
            if (Schema::hasColumn('orders', 'billing_details')) $colsToDrop[] = 'billing_details';
            
            if (!empty($colsToDrop)) {
                $table->dropColumn($colsToDrop);
            }
        });
    }
};
