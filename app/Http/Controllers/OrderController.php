<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $customer = Customer::where('user_id', $userId)->first();

        if (!$customer) {
            return view('customers.orders', [
                'orders' => collect(),
            ]);
        }

        $customerId = (string) $customer->id;

        // Fetch paginated orders
        $orders = Order::where('customer_id', $customerId)
            ->orderBy('orderdate', 'desc')
            ->paginate(5);

        // Attach items + products
        foreach ($orders as $order) {
            $items = OrderItem::where('order_id', (string) $order->_id)->get();

            $productIds = $items->pluck('product_id')->unique()->filter();
            $products = collect();
            if ($productIds->isNotEmpty()) {
                $products = Product::whereIn('id', $productIds)->get()->keyBy('id');
            }

            $order->itemsData = $items;
            $order->productsData = $products;
        }

        return view('customers.orders', compact('orders'));
    }

     public function show($orderId)
    {
        $order = Order::findOrFail($orderId);

        // Get order items
        $items = OrderItem::where('order_id', (string) $order->_id)->get();

        // Fetch products for those items
        $productIds = $items->pluck('product_id')->unique()->filter();
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        $order->itemsData = $items;
        $order->productsData = $products;

        return view('customers.order-tracking', compact('order'));
    }

    public function cancel($id)
    {
        $order = Order::findOrFail($id);

        // Only allow cancel if not delivered/cancelled already
        if (in_array(strtolower($order->orderstatus), ['delivered', 'cancelled'])) {
            return response()->json([
                'success' => false,
                'message' => 'This order cannot be cancelled.'
            ], 400);
        }

        $order->orderstatus = 'cancelled';
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Order cancelled successfully.',
            'order'   => $order
        ]);
    }

}
