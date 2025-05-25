<?php

namespace App\Http\Controllers\Client;

use App\Models\Carts;
use App\Models\Product;
use App\Models\Voucher;
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

        // Lọc các item lỗi
        $cartItems = $cartItems->filter(function ($item) {
            return $item->product && (!$item->variant_id || $item->variant);
        });

        // Tính tổng tiền an toàn
        $totalPrice = $cartItems->sum(function ($item) {
            $price = 0;

            if ($item->variant && $item->variant->price) {
                $price = $item->variant->price;
            } elseif ($item->product && $item->product->price) {
                $price = $item->product->price;
            }

            return $item->quantity * $price;
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
    protected function calculateCartTotal()
    {
        // Giả sử bạn lưu cart trong session hoặc DB
        $cart = session('cart', []);
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }
    public function update(Request $request, $id)
    {
        // Tìm cart item thuộc về người dùng hiện tại
        $cartItem = Carts::with(['product', 'variant'])
            ->where('id', $id)
            ->where(function ($q) {
                if (Auth::check()) {
                    $q->where('user_id', Auth::id());
                } else {
                    $q->where('session_id', Session::getId());
                }
            })
            ->firstOrFail();

        // Lấy tồn kho: ưu tiên variant nếu có, fallback về product
        $stock = $cartItem->variant->stock ?? $cartItem->product->stock ?? 0;

        $validatedData = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:' . $stock],
        ], [
            'quantity.required' => 'Vui lòng nhập số lượng.',
            'quantity.integer' => 'Số lượng phải là một số nguyên.',
            'quantity.min' => 'Số lượng phải lớn hơn hoặc bằng 1.',
            'quantity.max' => 'Số lượng yêu cầu vượt quá hàng tồn kho. Chỉ còn lại ' . $stock . ' sản phẩm.',
        ]);

        // Cập nhật số lượng
        $cartItem->quantity = $validatedData['quantity'];
        $cartItem->save();

        // Tính lại giá dòng này
        $unitPrice = $cartItem->variant->price ?? $cartItem->product->price ?? 0;
        $itemTotal = $unitPrice * $cartItem->quantity;

        // Lấy toàn bộ giỏ hàng hiện tại
        $cartItems = Carts::with(['product', 'variant'])
            ->where(function ($q) {
                if (Auth::check()) {
                    $q->where('user_id', Auth::id());
                } else {
                    $q->where('session_id', Session::getId());
                }
            })
            ->get();

        // Tính tổng tiền toàn bộ giỏ hàng
        $grandTotal = $cartItems->sum(function ($item) {
            $price = $item->variant->price ?? $item->product->price ?? 0;
            return $item->quantity * $price;
        });

        return response()->json([
            'success' => 'Cập nhật số lượng thành công.',
            'new_total_price' => number_format($cartItem->variant->price * $cartItem->quantity, 0, ',', '.'),
            'grand_total' => number_format($this->calculateCartTotal(auth()->id()), 0, ',', '.'),
            'discount' => session('voucher')['discount'] ?? 0
        ]);
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
    public function applyVoucher(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $voucher = Voucher::where('code', $request->code)
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('expires_at')->orWhere('expires_at', '>=', now());
            })
            ->first();

        if (!$voucher) {
            return back()->with('error', 'Mã giảm giá không hợp lệ hoặc đã hết hạn.');
        }

        // Lấy giỏ hàng
        if (Auth::check()) {
            $cartItems = Carts::with(['product', 'variant'])
                ->where('user_id', Auth::id())
                ->get();
        } else {
            $cartItems = Carts::with(['product', 'variant'])
                ->where('session_id', Session::getId())
                ->get();
        }

        if ($cartItems->isEmpty()) {
            return back()->with('error', 'Giỏ hàng trống, không thể áp dụng mã giảm giá.');
        }

        // Tổng tiền giỏ hàng
        $total = $cartItems->sum(function ($item) {
            return $item->variant->price * $item->quantity;
        });

        // Kiểm tra đơn hàng tối thiểu
        if (!is_null($voucher->min_order_amount) && $total < $voucher->min_order_amount) {
            return back()->with('error', 'Đơn hàng cần tối thiểu ' . number_format($voucher->min_order_amount) . ' VNĐ để sử dụng mã này.');
        }

        // Tính giảm giá
        $discount = 0;
        if ($voucher->type === 'fixed') {
            $discount = $voucher->value;
        } elseif ($voucher->type === 'percent') {
            $discount = ($voucher->value / 100) * $total;
        }

        // Giảm không được vượt quá tổng
        $discount = min($discount, $total);

        // Lưu vào session
        session([
            'voucher' => [
                'code' => $voucher->code,
                'discount' => $discount,
                'type' => $voucher->type, // <== THÊM DÒNG NÀY
                'percent' => $voucher->type === 'percent' ? $voucher->value : null,
            ]
        ]);


        return back()->with('success', 'Áp dụng mã giảm giá thành công!');
    }

    public function removeVoucher()
    {
        session()->forget('voucher');
        return back()->with('success', 'Đã huỷ mã giảm giá.');
    }
    public function listAvailableVouchers()
    {
        $now = now();

        $vouchers = Voucher::where('is_active', true)
            ->where(function ($q) use ($now) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', $now);
            })
            ->where(function ($q) use ($now) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>=', $now);
            })
            ->get();

        return view('client.voucher-list', compact('vouchers'));
    }
    public function miniCart()
    {
        $cartItems = Carts::with(['product', 'variant.color', 'variant.size'])
            ->where(function ($query) {
                if (Auth::check()) {
                    $query->where('user_id', Auth::id());
                } else {
                    $query->where('session_id', Session::getId());
                }
            })
            ->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['empty' => true]);
        }

        $items = [];

        foreach ($cartItems as $item) {
            // Nếu thiếu product hoặc variant (khi có variant_id) thì bỏ qua
            if (!$item->product || ($item->variant_id && !$item->variant)) {
                continue;
            }

            $variantData = null;
            if ($item->variant) {
                $variantData = $item->variant->toArray();
                $variantData['variant_name'] = $item->variant->variation_name ?? null;
                $variantData['color_name'] = $item->variant->color->code ?? null;
                $variantData['size_name'] = $item->variant->size->name ?? null;
            }

            $price = $item->variant ? $item->variant->price : $item->product->price;

            $items[] = [
                'name' => $variantData['variant_name'] ?? $item->product->name,
                'quantity' => $item->quantity,
                'price' => $price,
                'product' => [
                    'image' => $item->product->image,
                ],
                'variant' => $variantData,
            ];
        }

        if (empty($items)) {
            return response()->json(['empty' => true]);
        }

        return response()->json([
            'empty' => false,
            'items' => $items,
            'total_quantity' => collect($items)->sum('quantity'),
            'total_price' => collect($items)->sum(function ($item) {
                return $item['price'] * $item['quantity'];
            }),
        ]);
    }
}
