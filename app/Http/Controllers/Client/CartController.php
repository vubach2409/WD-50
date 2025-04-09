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
        'variant_id' => 'nullable|exists:product_variants,id',
        'quantity' => 'required|integer|min:1'
    ]);

    $product = Product::findOrFail($request->product_id);
    $variantId = $request->variant_id;
    $quantityToAdd = (int) $request->quantity;

    if ($variantId) {
        $variant = ProductVariant::where('id', $variantId)
            ->where('product_id', $product->id)
            ->first();

        if (!$variant) {
            return redirect()->back()->with('error', 'Biến thể sản phẩm không hợp lệ!');
        }

        // Lấy số lượng hiện tại trong giỏ hàng của biến thể đó (nếu có)
        $existingCart = Carts::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->where('variant_id', $variantId)
            ->first();

        $currentInCart = $existingCart ? $existingCart->quantity : 0;
        $totalRequested = $currentInCart + $quantityToAdd;

        if ($totalRequested > $variant->stock) {
            return redirect()->back()->with('error', 'Tổng số lượng trong giỏ vượt quá tồn kho!');
        }

        // Nếu đã tồn tại thì cập nhật
        if ($existingCart) {
            $existingCart->quantity += $quantityToAdd;
            $existingCart->save();
        } else {
            Carts::create([
                'user_id'    => auth()->id(),
                'product_id' => $product->id,
                'variant_id' => $variant->id,
                'quantity'   => $quantityToAdd,
            ]);
        }

    } else {
        // Sản phẩm không có biến thể => không cần kiểm tra tồn kho
        Carts::create([
            'user_id'    => auth()->id(),
            'product_id' => $product->id,
            'quantity'   => $quantityToAdd,
        ]);
    }

    return redirect()->back()->with('success', 'Đã thêm vào giỏ hàng!');
}





   // Cập nhật số lượng sản phẩm trong giỏ hàng
   public function update(Request $request, $id)
   {
       $cartItem = Carts::findOrFail($id);
   
       $quantity = (int) $request->input('quantity');
   
       if ($quantity < 1) {
           return redirect()->back()->with('error', 'Số lượng phải lớn hơn 0.');
       }
   
       // Kiểm tra tồn kho biến thể
       if ($quantity > $cartItem->variant->stock) {
           return redirect()->back()->with('error', 'Số lượng vượt quá tồn kho hiện có.');
       }
   
       $cartItem->quantity = $quantity;
       $cartItem->save();
   
       return redirect()->back()->with('success', 'Cập nhật số lượng thành công.');
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
