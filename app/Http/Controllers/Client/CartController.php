<?php

namespace App\Http\Controllers\Client;

use App\Models\Carts;
use App\Models\Product;
use Illuminate\Http\Request;
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
   $cartItems = Carts::where('user_id', Auth::id())->with('product')->get();
   $totalPrice = $cartItems->sum(function ($item) {
       return $item->quantity * $item->product->price;
   });

   return view('client.cart', compact('cartItems', 'totalPrice'));
}



   // Thêm sản phẩm vào giỏ hàng
   public function addToCart(Request $request)
{
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'quantity' => 'required|integer|min:1'
    ]);

    $product = Product::findOrFail($request->product_id);

    // Kiểm tra số lượng tồn kho
    if ($request->quantity > $product->stock) {
        return back()->with('error', 'Số lượng yêu cầu vượt quá tồn kho!');
    }

    $cartData = [
        'product_id' => $product->id,
        'quantity' => $request->quantity
    ];

    if (Auth::check()) {
        $cartData['user_id'] = Auth::id();
    } else {
        $cartData['session_id'] = Session::getId();
    }

    // Kiểm tra nếu sản phẩm đã có trong giỏ hàng
    $cart = Carts::where('product_id', $product->id)
        ->where(function ($query) {
            if (Auth::check()) {
                $query->where('user_id', Auth::id());
            } else {
                $query->where('session_id', Session::getId());
            }
        })->first();

    if ($cart) {
        $newQuantity = $cart->quantity + $request->quantity;
        if ($newQuantity > $product->stock) {
            return back()->with('error', 'Số lượng trong giỏ hàng vượt quá tồn kho!');
        }
        $cart->update(['quantity' => $newQuantity]);
    } else {
        Carts::create($cartData);
    }

    return back()->with('success', 'Sản phẩm đã được thêm vào giỏ hàng!');
}


   // Cập nhật số lượng sản phẩm trong giỏ hàng
   public function update(Request $request, $id)
   {
       $cartItem = Carts::find($id);
       if (!$cartItem) {
           return redirect()->back()->with('error', 'Sản phẩm không tồn tại trong giỏ hàng.');
       }
   
       // Kiểm tra số lượng nhập vào có vượt quá tồn kho không
       if ($request->quantity > $cartItem->product->stock) {
           return redirect()->back()->with('error', 'Số lượng vượt quá giới hạn kho hàng.');
       }
   
       // Cập nhật số lượng
       $cartItem->quantity = $request->quantity;
       $cartItem->save();
   
       return redirect()->back()->with('success', 'Cập nhật giỏ hàng thành công.');
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
