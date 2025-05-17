<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',  
        'variant_id',
        'variant_name',  
        'variant_sku',    
        'color_name',
        'size_name',    
        'price',
        'quantity',
        'variant_image',  
    ];

    // Quan hệ với bảng orders

    public function order()
    {
        return $this->belongsTo(Orders::class, 'order_id', 'id');
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
