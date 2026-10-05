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
        Schema::table('subscription_plans', function (Blueprint $table) {
            if (!Schema::hasColumn('subscription_plans', 'card_limit')) {
                $table->integer('card_limit')->default(1)->after('duration_days');
            }
            if (!Schema::hasColumn('subscription_plans', 'review_limit')) {
                $table->integer('review_limit')->default(50)->after('card_limit'); // -1 for unlimited
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscription_plans', function (Blueprint $table) {
            $table->dropColumn(['card_limit', 'review_limit']);
        });
    }
};
