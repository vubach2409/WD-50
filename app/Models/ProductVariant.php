<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id', 
        'color_id', 
        'size_id', 
        'variation_name', 
        'sku', 
        'price', 
        'weight', 
        'image', 
        'stock'
    ];
    public function color()
    {
        return $this->belongsTo(Color::class);
    }
    public function size()
    {
        return $this->belongsTo(Size::class);
    }
    protected static function booted()
    {
        static::saved(fn($variant) => $variant->product?->updateStock());
        static::deleted(fn($variant) => $variant->product?->updateStock());
    }
}
