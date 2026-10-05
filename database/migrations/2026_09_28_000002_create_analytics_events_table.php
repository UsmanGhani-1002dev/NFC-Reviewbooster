<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('analytics_events', function (Blueprint $table) {
            $table->id();
            $table->string('type', 40)->index();          // add_to_cart | checkout_started | purchase
            $table->string('visitor_id', 64)->nullable()->index();
            $table->string('session_id', 64)->nullable();
            $table->foreignId('user_id')->nullable();
            $table->foreignId('product_id')->nullable()->index();
            $table->foreignId('product_variant_id')->nullable();
            $table->unsignedInteger('quantity')->nullable();
            $table->decimal('value', 10, 2)->nullable();
            $table->foreignId('order_id')->nullable();
            $table->string('ip', 45)->nullable();
            $table->string('country', 100)->nullable();
            $table->timestamp('created_at')->nullable()->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('analytics_events');
    }
};
