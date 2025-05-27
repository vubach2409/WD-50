<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;
    public function getMinPriceAttribute()
    {
        if ($this->relationLoaded('variants') && $this->variants->isNotEmpty()) {
            return $this->variants->min('price');
        }
        return null;
    }

    public function getMaxPriceAttribute()
    {
        if ($this->relationLoaded('variants') && $this->variants->isNotEmpty()) {
            return $this->variants->max('price');
        }
        return null;
    }

    protected $fillable = [
        'name', 
        'description', 
        'image', 
        'product_detail', 
        'category_id', 
        'brand_id',
        'deleted_at',
        'short_description'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'product_id', 'id');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function feedbacks()
    {
        return $this->hasManyThrough(
            Feedbacks::class,
            ProductVariant::class,
            'product_id',   // Foreign key on ProductVariant table...
            'variation_id', // Foreign key on Feedbacks table...
            'id',           // Local key on Product table...
            'id'            // Local key on ProductVariant table...
        )->latest();
    }
public function updateStock()
    {
        $totalStock = $this->variants->sum('stock');
        $this->stock = $totalStock;
        $this->save();
    }
}
