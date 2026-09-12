<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
     'order_code', 'user_id',
        'customer_name', 'customer_email', 'customer_phone',
        'customer_address', 'customer_note', 'discount_amount', 'coupon_code', 'shipping_fee',
        'total_amount', 'payment_method', 'payment_status', 'paid_at',
        'payment_transaction_id', 'status', 'confirmed_at', 'shipped_at',
        'completed_at', 'cancelled_at', 'cancel_reason', 'admin_note',
    ];

    protected $casts = [
        'discount_amount' => 'decimal:0',
        'shipping_fee' => 'decimal:0',
        'total_amount' => 'decimal:0',
        'paid_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'shipped_at' => 'datetime',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

     public function getSubtotalAttribute(): int
    {
        return $this->items->sum('line_total');
    }
}
