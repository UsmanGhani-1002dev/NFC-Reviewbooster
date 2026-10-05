<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('abandoned_carts', function (Blueprint $table) {
            $table->id();
            // One row per checkout (re-used Stripe PaymentIntent) so repeated
            // page loads / delivery-method changes update the same record.
            $table->string('payment_intent_id')->nullable()->unique();
            $table->string('session_id')->nullable()->index();
            $table->string('visitor_id')->nullable()->index();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('customer_email')->nullable()->index();
            $table->json('items')->nullable();
            $table->unsignedInteger('item_count')->default(0);
            $table->decimal('items_total', 10, 2)->default(0);
            $table->decimal('shipping_fee', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->string('shipping_method')->nullable();
            // abandoned = started checkout but not paid; converted = order placed.
            $table->string('status')->default('abandoned')->index();
            $table->unsignedBigInteger('converted_order_id')->nullable();
            $table->timestamp('recovered_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('abandoned_carts');
    }
};
