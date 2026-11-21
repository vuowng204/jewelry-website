<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carts', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('cart_items', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('cart_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('quantity');
            $table->unique(['cart_id', 'product_id']);
            $table->timestamps();
        });

        Schema::table('orders', function (Blueprint $table): void {
            $table->uuid('checkout_token')->nullable();
            $table->unique(['user_id', 'checkout_token']);
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            $table->dropUnique(['user_id', 'checkout_token']);
            $table->dropColumn('checkout_token');
        });

        Schema::dropIfExists('cart_items');
        Schema::dropIfExists('carts');
    }
};
