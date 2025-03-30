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

        $subtotal = 0;
        $items = [];
        foreach ($cart as $productId => $details) {
            $product = \App\Models\Product::find($productId);
            if ($product) {
                $itemSubtotal = $details['quantity'] * $details['price'];
                $subtotal += $itemSubtotal;
                $items[] = [
                    'id' => $productId,
                    'name' => $details['name'],
                    'price' => $details['price'],
                    'quantity' => $details['quantity'],
                    'image' => $details['image'],
                    'subtotal' => $itemSubtotal,
                    'stock' => $product->stock
                ];
            }
        }

        // Default shipping cost (standard shipping)
        $shippingCost = 5.00;
        $total = $subtotal + $shippingCost;

        return view('client.checkout', compact('items', 'subtotal', 'shippingCost', 'total'));
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
            'payment_method' => 'required|in:cod,paypal',
            'shipping_method' => 'required|in:standard,express'
        ]);

        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart')->with('error', 'Your cart is empty.');
        }

        try {
            DB::beginTransaction();

            // Calculate subtotal
            $subtotal = 0;
            foreach ($cart as $productId => $details) {
                $product = \App\Models\Product::find($productId);
                if ($product) {
                    $subtotal += $details['quantity'] * $details['price'];
                }
            }

            // Calculate shipping cost
            $shippingCost = $request->shipping_method === 'express' ? 15.00 : 5.00;
            $total = $subtotal + $shippingCost;

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
                'payment_method' => $request->payment_method,
                'shipping_method' => $request->shipping_method,
                'shipping_fee' => $shippingCost
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
