<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Models\Cart;
use Illuminate\Support\Facades\Session;

class SyncCartAfterLogin
{
    public function handle(Login $event)
    {
        $user = $event->user;
        $cartSession = Session::get('cart', []);

        foreach ($cartSession as $productId => $item) {
            $cartItem = Cart::where('user_id', $user->id)->where('product_id', $productId)->first();
            if ($cartItem) {
                $cartItem->increment('quantity', $item['quantity']);
            } else {
                Cart::create([
                    'user_id' => $user->id,
                    'product_id' => $productId,
                    'quantity' => $item['quantity'],
                ]);
            }
        }

        Session::forget('cart');
    }
}


