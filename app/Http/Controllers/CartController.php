<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product; // MySQL Product
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CartController extends Controller
{
    /**
     * API: Get cart items (protected by Sanctum)
     */
    public function index(Request $request)
    {
        $cart = Cart::where('customer_id', (string) Auth::id())->first();

        if (!$cart) {
            return response()->json([
                'success' => true,
                'data' => [
                    'items' => [],
                    'total' => 0,
                    'count' => 0
                ]
            ]);
        }

        $items = CartItem::where('cart_id', $cart->_id)->get();
        $total = 0;

        // Load product details from MySQL
        foreach ($items as $item) {
            $product = Product::find($item->product_id);
            $item->product = $product ? [
                'id' => $product->id,
                'name' => $product->name,
                'image' => $product->image ? Storage::url($product->image) : null,
                'type' => $product->type,
            ] : null;
            $total += $item->quantity * $item->unitprice;
        }

        return response()->json([
            'success' => true,
            'data' => [
                'items' => $items,
                'total' => $total,
                'count' => $items->sum('quantity')
            ]
        ]);
    }

    /**
     * Web: Display cart page (for blade views)
     */
    public function webIndex()
    {
        $cart = Cart::where('customer_id', (string) Auth::id())->first();
        $items = [];
        $total = 0;

        if ($cart) {
            $items = CartItem::where('cart_id', $cart->_id)->get();
            
            // Load product details from MySQL
            foreach ($items as $item) {
                $item->product = Product::find($item->product_id);
                $total += $item->quantity * $item->unitprice;
            }
        }

        return view('customer.cart', compact('cart', 'items', 'total'));
    }

    /**
     * API: Add product to cart (protected by Sanctum)
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1|max:10',
            'size'       => 'nullable|string',
        ]);

        try {
            $product = Product::findOrFail($request->product_id);

            // Check stock if size provided
            if ($request->size) {
                $stockData = $product->stockquantity;
                if (!isset($stockData[$request->size]) || $stockData[$request->size] < $request->quantity) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Not enough stock available.'
                    ], 400);
                }
            }

            // Find or create cart
            $cart = Cart::firstOrCreate(['customer_id' => (string) Auth::id()]);

            // Check existing item
            $existingItem = CartItem::where('cart_id', $cart->_id)
                ->where('product_id', $product->id)
                ->where('size', $request->size)
                ->first();

            if ($existingItem) {
                $existingItem->quantity += $request->quantity;
                $existingItem->save();
            } else {
                CartItem::create([
                    'cart_id'    => $cart->_id,
                    'product_id' => $product->id,
                    'quantity'   => $request->quantity,
                    'size'       => $request->size,
                    'unitprice'  => $product->price,
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Product added to cart successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add product to cart.'
            ], 500);
        }
    }

    /**
     * API: Update cart item quantity (protected by Sanctum)
     */
    public function update(Request $request, $itemId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:10',
        ]);

        $item = CartItem::find($itemId);
        
        if (!$item) {
            return response()->json([
                'success' => false,
                'message' => 'Cart item not found.'
            ], 404);
        }

        // Verify ownership
        $cart = Cart::where('_id', $item->cart_id)
            ->where('customer_id', (string) Auth::id())
            ->first();

        if (!$cart) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access.'
            ], 403);
        }

        // ✅ Load product from MySQL
        $product = Product::find($item->product_id);
        if ($product && $item->size) {
            $stockData = $product->stockquantity;
            $availableStock = $stockData[$item->size] ?? 0;

            if ($request->quantity > $availableStock) {
                return response()->json([
                    'success' => false,
                    'message' => 'Not enough stock available.'
                ], 400);
            }
        }

        // ✅ Update if within stock
        $item->quantity = $request->quantity;
        $item->save();

        return response()->json([
            'success' => true,
            'message' => 'Cart updated successfully!'
        ]);
    }


    /**
     * API: Remove item from cart (protected by Sanctum)
     */
    public function destroy($itemId)
    {
        $item = CartItem::find($itemId);
        
        if (!$item) {
            return response()->json([
                'success' => false,
                'message' => 'Cart item not found.'
            ], 404);
        }

        // Verify ownership
        $cart = Cart::where('_id', $item->cart_id)
            ->where('customer_id', (string) Auth::id())
            ->first();

        if (!$cart) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access.'
            ], 403);
        }

        $item->delete();

        return response()->json([
            'success' => true,
            'message' => 'Item removed from cart!'
        ]);
    }

    /**
     * Get cart count for navbar
     */
    public function getCartCount()
    {
        $cart = Cart::where('customer_id', (string) Auth::id())->first();
        if (!$cart) return 0;
        
        return CartItem::where('cart_id', $cart->_id)->sum('quantity');
    }
}
