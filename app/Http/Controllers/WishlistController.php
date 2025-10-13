<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wishlist;
use App\Models\WishlistItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * GET /api/wishlist → return wishlist items
     */
    public function index()
    {
        // Get the correct customer_id from the logged-in user
        $customerId = Auth::user()->customer->id;

        $wishlist = Wishlist::firstOrCreate([
            'customer_id' => $customerId,
        ]);

        $items = WishlistItem::with('product')
            ->where('wishlist_id', $wishlist->id)
            ->get();

        return response()->json([
            'success' => true,
            'items' => $items,
        ]);
    }

    /**
     * POST /api/wishlist → add product
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        // Use customer_id, not user_id
        $customerId = Auth::user()->customer->id;

        $wishlist = Wishlist::firstOrCreate([
            'customer_id' => $customerId,
        ]);

        $exists = WishlistItem::where('wishlist_id', $wishlist->id)
            ->where('product_id', $request->product_id)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Product already in wishlist',
            ], 409);
        }

        $item = WishlistItem::create([
            'wishlist_id' => $wishlist->id,
            'product_id' => $request->product_id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Product added to wishlist',
            'item' => $item->load('product'),
        ]);
    }

    /**
     * DELETE /api/wishlist/{id} → remove product
     */
    public function destroy($id)
    {
        $customerId = Auth::user()->customer->id;

        $wishlist = Wishlist::where('customer_id', $customerId)->first();

        if (!$wishlist) {
            return response()->json(['success' => false, 'message' => 'Wishlist not found'], 404);
        }

        $item = WishlistItem::where('wishlist_id', $wishlist->id)
            ->where('id', $id)
            ->first();

        if (!$item) {
            return response()->json(['success' => false, 'message' => 'Item not found'], 404);
        }

        $item->delete();

        return response()->json(['success' => true, 'message' => 'Item removed']);
    }
}
