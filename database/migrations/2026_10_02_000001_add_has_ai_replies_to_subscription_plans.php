<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subscription_plans', function (Blueprint $table) {
            // Per-plan toggle for the AI review-reply assistant (replaces the
            // old "plan name contains premium" check).
            $table->boolean('has_ai_replies')->default(false)->after('review_limit');
        });
    }

    public function down(): void
    {
        Schema::table('subscription_plans', function (Blueprint $table) {
            $table->dropColumn('has_ai_replies');
        });
    }
};
