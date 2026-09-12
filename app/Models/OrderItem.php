<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    /** @use HasFactory<\Database\Factories\OrderItemFactory> */
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'product_slug',
        'product_thumbnail',
        'product_material',
        'unit_price',
        'original_price',
        'quantity',
        'line_total',
    ];

    // ================== ÉP KIỂU DỮ LIỆU ==================
    protected $casts = [
        'order_id'       => 'integer',
        'product_id'     => 'integer',
        'unit_price'     => 'integer',
        'original_price' => 'integer',
        'quantity'       => 'integer',
        'line_total'     => 'integer',
        'created_at'     => 'datetime',
        'updated_at'     => 'datetime',
    ];

    // ================== QUAN HỆ ==================
    public function order(){
        return $this->belongsTo(Order::class);
    }

    public function product(){
        return $this->belongsTo(Product::class);
    }
}
