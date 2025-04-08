<?php

namespace App\Http\Controllers\Client;

use App\Models\Carts;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
   // Hiển thị giỏ hàng
   public function index()
   {
       return response()->json(Carts::getCart());
   }
   public function showCart()
{
    if (Auth::check()) {
        $cartItems = Carts::with(['product', 'variant'])
            ->where('user_id', Auth::id())
            ->get();
    } else {
        $cartItems = Carts::with(['product', 'variant'])
            ->where('session_id', Session::getId())
            ->get();
    }

   $cartItems = Carts::where('user_id', Auth::id())->with('product')->get();
   $totalPrice = $cartItems->sum(function ($item) {
       return $item->quantity * $item->product->price;
   });

   return view('client.cart', compact('cartItems', 'totalPrice'));
}



public function addToCart(Request $request)
{
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'variant_id' => 'required|exists:product_variants,id',
        'quantity' => 'required|integer|min:1'
    ]);

    $product = Product::findOrFail($request->product_id);
    $variantId = $request->variant_id;

    if ($variantId) {
        $variant = ProductVariant::where('id', $variantId)
                    ->where('product_id', $product->id)
                    ->first();

        if (!$variant) {
            return back()->with('error', 'Biến thể sản phẩm không hợp lệ!');
        }

        if ($variant->stock < $request->quantity) {
            return back()->with('error', 'Số lượng biến thể không đủ!');
        }

        Carts::create([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
            'variant_id' => $variant->id,
            'quantity' => $request->quantity,
        ]);
    } else {
        // Trường hợp không chọn biến thể
        if ($product->stock < $request->quantity) {
            return back()->with('error', 'Sản phẩm không đủ hàng!');
        }

        Carts::create([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
            'quantity' => $request->quantity,
        ]);
    }

    return back()->with('success', 'Đã thêm vào giỏ hàng!');
}




   // Cập nhật số lượng sản phẩm trong giỏ hàng
   public function update(Request $request, $id)
   {
       $cartItem = Carts::findOrFail($id);
   
       $quantity = (int) $request->input('quantity');
   
       if ($quantity < 1) {
           return back()->with('error', 'Số lượng phải lớn hơn 0.');
       }
   
       // Kiểm tra tồn kho biến thể
       if ($quantity > $cartItem->variant->stock) {
           return back()->with('error', 'Số lượng vượt quá tồn kho hiện có.');
       }
   
       $cartItem->quantity = $quantity;
       $cartItem->save();
   
       return back()->with('success', 'Cập nhật số lượng thành công.');
   }
   
   
   
   

   // Xóa sản phẩm khỏi giỏ hàng
   public function removeFromCart($id)
   {
       $cart = Carts::where('id', $id)->where(function ($query) {
           if (Auth::check()) {
               $query->where('user_id', Auth::id());
           } else {
               $query->where('session_id', Session::getId());
           }
       })->firstOrFail();

       $cart->delete();

       return redirect()->back()->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng!');
   }

   // Xóa toàn bộ giỏ hàng
   public function clearCart()
   {
       Carts::where(function ($query) {
           if (Auth::check()) {
               $query->where('user_id', Auth::id());
           } else {
               $query->where('session_id', Session::getId());
           }
       })->delete();

       return redirect()->back()->with('success', 'Đã xóa toàn bộ giỏ hàng!');
   }
}
