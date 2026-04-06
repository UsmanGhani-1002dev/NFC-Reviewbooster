<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('subtitle')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->string('badge_text')->nullable(); // e.g. "#1 Google Review Card in the UK"
            $table->json('features')->nullable(); // ["Works on iPhone & Android", "No app required", "£0 monthly fees"]
            $table->json('gallery')->nullable(); // additional product images
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('name'); // e.g. "1 Rating Card + free stand"
            $table->integer('quantity')->default(1);
            $table->decimal('price', 8, 2); // sale price
            $table->decimal('original_price', 8, 2); // original/compare price
            $table->integer('discount_percent')->default(0);
            $table->boolean('is_best_value')->default(false);
            $table->boolean('is_most_popular')->default(false);
            $table->integer('stock')->default(50);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variants');
        Schema::dropIfExists('products');
    }
};
