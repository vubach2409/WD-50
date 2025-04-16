<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Carts extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'product_id', 'quantity', 'session_id','variant_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id');
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Kiểm tra giỏ hàng của user hoặc session
    public static function getCart()
    {
        if (Auth::check()) {
            return self::where('user_id', Auth::id())->with('product')->get();
        }

        return self::where('session_id', Session::getId())->with('product')->get();
    }
}
