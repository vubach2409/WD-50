<?php
namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Models\Cart;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MergeCartAfterLogin
{
    public function handle(Login $event)
    {
        $sessionCart = Cart::where('session_id', Session::getId())->get();

        foreach ($sessionCart as $cartItem) {
            Cart::updateOrCreate(
                ['user_id' => Auth::id(), 'product_id' => $cartItem->product_id],
                ['quantity' => DB::raw("quantity + {$cartItem->quantity}")]
            );
        }

        // Xóa giỏ hàng session sau khi hợp nhất
        Cart::where('session_id', Session::getId())->delete();
    }
}

