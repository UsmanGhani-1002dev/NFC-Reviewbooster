<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_views', function (Blueprint $table) {
            $table->id();
            $table->string('visitor_id', 64)->index();   // persistent cookie id
            $table->string('session_id', 64)->nullable();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip', 45)->nullable();
            $table->string('country', 100)->nullable()->index();
            $table->string('country_code', 2)->nullable();
            $table->string('path', 500)->nullable();
            $table->string('route_name', 150)->nullable();
            $table->foreignId('product_id')->nullable()->index();
            $table->string('referrer', 500)->nullable();
            $table->string('device', 20)->nullable();     // mobile/desktop/tablet
            $table->text('user_agent')->nullable();
            $table->timestamp('created_at')->nullable()->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_views');
    }
};
