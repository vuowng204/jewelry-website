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
        Schema::create('products', function (Blueprint $table) {
         $table->id();

            $table->foreignId('category_id')
                  ->constrained('categories')   // chỉ định rõ bảng cho chắc
                  ->cascadeOnDelete();

            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();

            $table->decimal('price', 12, 0);
            $table->decimal('sale_price', 12, 0)->nullable();

            $table->integer('stock')->default(0);
            $table->string('material')->nullable();
            $table->string('thumbnail')->nullable();

            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);

            $table->timestamps();

            $table->index(['category_id', 'is_active']);
            $table->index(['is_featured', 'is_active']);
    });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
