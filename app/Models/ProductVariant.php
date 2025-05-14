<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariant extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'product_id', 
        'color_id', 
        'size_id', 
        'variation_name', 
        'sku', 
        'price', 
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
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}
