<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    // Hiển thị giỏ hàng
    public function index()
    {
        return response()->json(Cart::getCart());
    }
    public function showCart()
{
    $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();
    $totalPrice = $cartItems->sum(function ($item) {
        return $item->quantity * $item->product->price;
    });

    return view('cart.index', compact('cartItems', 'totalPrice'));
}



    // Thêm sản phẩm vào giỏ hàng
    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $cartData = [
            'product_id' => $request->product_id,
            'quantity' => $request->quantity
        ];

        if (Auth::check()) {
            $cartData['user_id'] = Auth::id();
        } else {
            $cartData['session_id'] = Session::getId();
        }

        $cart = Cart::updateOrCreate(
            ['product_id' => $request->product_id, 'user_id' => $cartData['user_id'] ?? null, 'session_id' => $cartData['session_id'] ?? null],
            ['quantity' => DB::raw("quantity + {$request->quantity}")]
        );

        return response()->json(['message' => 'Sản phẩm đã được thêm vào giỏ hàng!', 'cart' => $cart]);
    }

    // Cập nhật số lượng sản phẩm trong giỏ hàng
    public function updateCart(Request $request, $id)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);

        $cart = Cart::where('id', $id)->where(function ($query) {
            if (Auth::check()) {
                $query->where('user_id', Auth::id());
            } else {
                $query->where('session_id', Session::getId());
            }
        })->firstOrFail();

        $cart->update(['quantity' => $request->quantity]);

        return response()->json(['message' => 'Cập nhật giỏ hàng thành công!', 'cart' => $cart]);
    }

    // Xóa sản phẩm khỏi giỏ hàng
    public function removeFromCart($id)
    {
        $cart = Cart::where('id', $id)->where(function ($query) {
            if (Auth::check()) {
                $query->where('user_id', Auth::id());
            } else {
                $query->where('session_id', Session::getId());
            }
        })->firstOrFail();

        $cart->delete();

        return response()->json(['message' => 'Đã xóa sản phẩm khỏi giỏ hàng!']);
    }

    // Xóa toàn bộ giỏ hàng
    public function clearCart()
    {
        Cart::where(function ($query) {
            if (Auth::check()) {
                $query->where('user_id', Auth::id());
            } else {
                $query->where('session_id', Session::getId());
            }
        })->delete();

        return response()->json(['message' => 'Đã xóa toàn bộ giỏ hàng!']);
    }
}
