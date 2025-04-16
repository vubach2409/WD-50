<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name', 
        'description', 
        'price', 
        'image', 
        'stock', 
        'product_detail', 
        'category_id', 
        'brand_id',
        'deleted_at',
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
    public function updateStock()
    {
        $this->stock = $this->variants()->sum('stock');
        $this->save();
    }
    
}
