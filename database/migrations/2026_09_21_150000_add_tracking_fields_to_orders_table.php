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
            if (!Schema::hasColumn('orders', 'tracking_number')) {
                $table->string('tracking_number')->nullable()->after('notes');
            }
            if (!Schema::hasColumn('orders', 'carrier')) {
                $table->string('carrier')->default('Royal Mail')->nullable()->after('tracking_number');
            }
            if (!Schema::hasColumn('orders', 'tracking_notified_at')) {
                $table->timestamp('tracking_notified_at')->nullable()->after('carrier');
            }
            if (!Schema::hasColumn('orders', 'shipped_at')) {
                $table->timestamp('shipped_at')->nullable()->after('tracking_notified_at');
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
            if (Schema::hasColumn('orders', 'tracking_number')) $colsToDrop[] = 'tracking_number';
            if (Schema::hasColumn('orders', 'carrier')) $colsToDrop[] = 'carrier';
            if (Schema::hasColumn('orders', 'tracking_notified_at')) $colsToDrop[] = 'tracking_notified_at';
            if (Schema::hasColumn('orders', 'shipped_at')) $colsToDrop[] = 'shipped_at';

            if (!empty($colsToDrop)) {
                $table->dropColumn($colsToDrop);
            }
        });
    }
};
