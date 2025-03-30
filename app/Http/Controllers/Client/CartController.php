<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $total = 0;
        $items = [];

        foreach ($cart as $productId => $details) {
            $product = Product::find($productId);
            if ($product) {
                $items[] = [
                    'id' => $productId,
                    'name' => $details['name'],
                    'quantity' => $details['quantity'],
                    'price' => $details['price'],
                    'image' => $details['image'],
                    'subtotal' => $details['quantity'] * $details['price']
                ];
                $total += $details['quantity'] * $details['price'];
            }
        }

        return view('client.cart', compact('items', 'total'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($request->product_id);
        
        if ($product->stock < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Not enough stock available'
            ], 400);
        }

        // Get the current cart from session
        $cart = session()->get('cart', []);
        
        // If product already exists in cart, update quantity
        if (isset($cart[$request->product_id])) {
            $cart[$request->product_id]['quantity'] += $request->quantity;
        } else {
            // Add new product to cart
            $cart[$request->product_id] = [
                'name' => $product->name,
                'quantity' => $request->quantity,
                'price' => $product->price,
                'image' => $product->image
            ];
        }
        
        // Update cart in session
        session()->put('cart', $cart);
        
        // Prepare cart data for response
        $cartData = $this->prepareCartData($cart);
        
        return response()->json([
            'success' => true,
            'message' => 'Product added to cart successfully',
            'cart_count' => count($cart),
            'cart_data' => $cartData
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($request->product_id);
        
        if ($product->stock < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Not enough stock available'
            ], 400);
        }

        $cart = session()->get('cart', []);
        
        if (isset($cart[$request->product_id])) {
            $cart[$request->product_id]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
            
            // Prepare cart data for response
            $cartData = $this->prepareCartData($cart);
            
            return response()->json([
                'success' => true,
                'message' => 'Cart updated successfully',
                'cart_count' => count($cart),
                'cart_data' => $cartData
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Product not found in cart'
        ], 404);
    }

    public function remove(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $cart = session()->get('cart', []);
        
        if (isset($cart[$request->product_id])) {
            unset($cart[$request->product_id]);
            session()->put('cart', $cart);
            
            // Prepare cart data for response
            $cartData = $this->prepareCartData($cart);
            
            return response()->json([
                'success' => true,
                'message' => 'Product removed from cart',
                'cart_count' => count($cart),
                'cart_data' => $cartData
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Product not found in cart'
        ], 404);
    }

    private function prepareCartData($cart)
    {
        $items = [];
        $total = 0;

        foreach ($cart as $productId => $details) {
            $product = Product::find($productId);
            if ($product) {
                $items[] = [
                    'id' => $productId,
                    'name' => $details['name'],
                    'quantity' => $details['quantity'],
                    'price' => $details['price'],
                    'image' => $details['image']
                ];
                $total += $details['quantity'] * $details['price'];
            }
        }

        return [
            'count' => count($cart),
            'items' => $items,
            'total' => $total
        ];
    }
}
