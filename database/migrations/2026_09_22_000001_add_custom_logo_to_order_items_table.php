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
            if (!Schema::hasColumn('order_items', 'custom_logo_path')) {
                // Path (on the public disk) to the custom logo/artwork the
                // customer uploaded for the "Custom Company Logo & Design"
                // add-on. Null when no custom branding was requested.
                $table->string('custom_logo_path')->nullable()->after('locations');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            if (Schema::hasColumn('order_items', 'custom_logo_path')) {
                $table->dropColumn('custom_logo_path');
            }
        });
    }
};
