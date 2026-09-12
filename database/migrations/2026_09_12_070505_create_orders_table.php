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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->string('order_code')->unique(); // mã đơn hàng hiển thị cho khác hàng
            $table->foreignId('user_id'
            )->constrained('users')
                ->cascadeOnDelete();
            
            // thông tin khách hàng
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');
            $table->text('customer_address');
            $table->text('customer_note')->nullable();
        
            $table->decimal('discount_amount', 14, 0)->default(0);
            $table->string('coupon_code')->nullable();
            $table->decimal('shipping_fee', 14, 0)->default(0);
            $table->decimal('total_amount', 14, 0); // tổng tiền cuối cùng sau khi áp dụng giảm giá và phí vận chuyển

             // ---- Thanh toán ----
            // cod | bank_transfer | vnpay | momo | zalopay | credit_card
            $table->string('payment_method', 30)->default('cod');
            // unpaid | paid | refunded | failed
            $table->string('payment_status', 20)->default('unpaid');
            $table->timestamp('paid_at')->nullable();
            $table->string('payment_transaction_id')->nullable();

            // pending | confirmed | processing | shipping | completed | cancelled | returned
            $table->string('status', 20)->default('pending');
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->string('cancel_reason')->nullable();

             // Ghi chú nội bộ của admin
            $table->text('admin_note')->nullable();

            $table->softDeletes();

            $table->index(['status', 'created_at']);
            $table->index(['user_id', 'status']);
            $table->index('payment_status');

        
        
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
