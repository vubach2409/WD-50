<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $fillable = ['name','description','price','image','stock','product_detail','category_id','brand_id']; //add other field to mass-assignment

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Define the relationship with the Brand model
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
}
