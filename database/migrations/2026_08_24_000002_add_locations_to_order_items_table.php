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
        Schema::table('order_items', function (Blueprint $table) {
            if (!Schema::hasColumn('order_items', 'locations')) {
                // The Google Business location(s) linked to this specific line
                // (card pack), so each item's locations are known individually
                // rather than only aggregated at the order level.
                $table->json('locations')->nullable()->after('total');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            if (Schema::hasColumn('order_items', 'locations')) {
                $table->dropColumn('locations');
            }
        });
    }
};
