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
        Schema::table('cards', function (Blueprint $table) {
            $table->text('google_review_link')->nullable()->change();
        });

        Schema::table('manage_businesses', function (Blueprint $table) {
            $table->text('google_review_link')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cards', function (Blueprint $table) {
            $table->string('google_review_link', 255)->change();
        });

        Schema::table('manage_businesses', function (Blueprint $table) {
            $table->string('google_review_link', 255)->nullable()->change();
        });
    }
};
