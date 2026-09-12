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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')
                ->constrained('orders')
                ->cascadeOnDelete();
            
            $table->foreignId('product_id')
            ->nullable()
            ->constrained('products')
            ->nullOnDelete();

            $table->string('product_name');
            $table->string('product_slug')->nullable();
            $table->string('product_thumbnail')->nullable();
            $table->string('product_material')->nullable();

            // giá
            $table->decimal('unit_price', 14, 0); // giá bán tại thời điểm đặt hàng đã áp dụng giảm giá nếu có
            $table->decimal('original_price', 14, 0)->nullable();
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('line_total', 14, 0); // đơn giá * số lượng

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
