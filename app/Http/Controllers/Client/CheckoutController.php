<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart')->with('error', 'Your cart is empty.');
        }

        $total = 0;
        $items = [];
        foreach ($cart as $productId => $details) {
            $product = \App\Models\Product::find($productId);
            if ($product) {
                $subtotal = $details['quantity'] * $details['price'];
                $total += $subtotal;
                $items[] = [
                    'id' => $productId,
                    'name' => $details['name'],
                    'price' => $details['price'],
                    'quantity' => $details['quantity'],
                    'image' => $details['image'],
                    'subtotal' => $subtotal,
                    'stock' => $product->stock
                ];
            }
        }

        return view('client.checkout', compact('items', 'total'));
    }

    public function placeOrder(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'subdistrict' => 'required|string|max:100',
            'zip_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'payment_method' => 'required|in:cod,paypal'
        ]);

        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart')->with('error', 'Your cart is empty.');
        }

        try {
            DB::beginTransaction();

            // Calculate total
            $total = 0;
            foreach ($cart as $productId => $details) {
                $product = \App\Models\Product::find($productId);
                if ($product) {
                    $total += $details['quantity'] * $details['price'];
                }
            }

            // Create order
            $order = Order::create([
                'user_id' => auth()->id(),
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'city' => $request->city,
                'subdistrict' => $request->subdistrict,
                'zip_code' => $request->zip_code,
                'country' => $request->country,
                'total' => $total,
                'status' => 'pending',
                'payment_method' => $request->payment_method
            ]);

            // Create order items
            foreach ($cart as $productId => $details) {
                $product = \App\Models\Product::find($productId);
                if ($product) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $productId,
                        'quantity' => $details['quantity'],
                        'price' => $details['price'],
                        'subtotal' => $details['quantity'] * $details['price']
                    ]);

                    // Update product stock
                    $product->stock -= $details['quantity'];
                    $product->save();
                }
            }

            DB::commit();

            // Clear cart
            session()->forget('cart');

            // Redirect based on payment method
            if ($request->payment_method === 'paypal') {
                return redirect()->route('paypal.process', $order->id);
            }

            return redirect()->route('thankyou')->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'An error occurred while placing your order. Please try again.');
        }
    }
}
