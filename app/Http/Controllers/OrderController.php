<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // ✅ Fetch all orders of logged-in customer with items + payment
            $orders = Order::where('customer_id', Auth::user()->customer->id)
                ->with(['items.product', 'payment'])
                ->latest('orderdate')
                ->get();

            return view('orders.index', compact('orders'));
        } catch (Exception $e) {
            Log::error('Error fetching orders: ' . $e->getMessage());
            return back()->with('error', 'Unable to fetch orders. Please try again.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id'    => 'required|string',
            'firstname'      => 'required|string|max:100',
            'lastname'       => 'required|string|max:100',
            'email'          => 'required|email',
            'phone'          => 'required|string',
            'house_number'   => 'required|string',
            'street'         => 'required|string',
            'city'           => 'required|string',
            'postal_code'    => 'required|string',
            'payment_method' => 'required|in:cod,card,online',
            'totalprice'     => 'required|numeric|min:0',
            'items'          => 'required|array|min:1',
            'items.*.product_id' => 'required|string',
            'items.*.quantity'   => 'required|integer|min:1',
            'items.*.price'      => 'required|numeric|min:0',
        ]);

        // Use database transaction for data consistency
        try {
            DB::beginTransaction();

            // ✅ Create Order in MongoDB
            $order = Order::create([
                'customer_id'    => $request->customer_id,
                'firstname'      => $request->firstname,
                'lastname'       => $request->lastname,
                'email'          => $request->email,
                'phone'          => $request->phone,
                'house_number'   => $request->house_number,
                'street'         => $request->street,
                'city'           => $request->city,
                'postal_code'    => $request->postal_code,
                'payment_method' => $request->payment_method,
                'orderdate'      => now(),
                'orderstatus'    => 'pending',
                'totalprice'     => $request->totalprice,
                'order_number'   => $this->generateOrderNumber(), // Add unique order number
            ]);

            Log::info("✅ Order created: ", ['order_id' => $order->_id]);

            // ✅ Save order items in MongoDB
            $totalCalculated = 0;
            foreach ($request->items as $item) {
                $orderItem = OrderItem::create([
                    'order_id'      => $order->_id,
                    'product_id'    => $item['product_id'],
                    'orderquantity' => $item['quantity'],
                    'ordersize'     => $item['size'] ?? null,
                    'orderprice'    => $item['price'],
                ]);
                
                $totalCalculated += $item['price'] * $item['quantity'];
                Log::info("✅ Order item created: ", $orderItem->toArray());
            }

            // Verify total price matches calculated total
            if (abs($totalCalculated - $request->totalprice) > 0.01) {
                Log::warning("Price mismatch detected", [
                    'calculated' => $totalCalculated,
                    'submitted' => $request->totalprice
                ]);
            }

            // ✅ Save payment in MongoDB
            $payment = Payment::create([
                'order_id'       => $order->_id,
                'paymentmethod'  => $request->payment_method,
                'amount'         => $request->totalprice,
                'paymentdate'    => now(),
            ]);

            Log::info("✅ Payment created: ", $payment->toArray());

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully!',
                'order'   => $order->load(['items.product', 'payment']),
                'order_number' => $order->order_number,
            ], 201);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Order creation failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to place order. Please try again.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $order = Order::with(['items.product', 'payment'])->findOrFail($id);

            // Check if user has permission to view this order
            if (Auth::check() && $order->customer_id !== Auth::user()->customer->id) {
                abort(403, 'Unauthorized access to order.');
            }

            return view('orders.show', compact('order'));
        } catch (Exception $e) {
            Log::error('Error fetching order: ' . $e->getMessage());
            return back()->with('error', 'Order not found.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'orderstatus' => 'required|string|in:pending,confirmed,processing,shipped,delivered,cancelled',
        ]);

        try {
            $order = Order::findOrFail($id);

            $oldStatus = $order->orderstatus;
            $order->update([
                'orderstatus' => $request->orderstatus,
                'status_updated_at' => now(),
            ]);

            // Log status change
            Log::info("Order status updated", [
                'order_id' => $id,
                'old_status' => $oldStatus,
                'new_status' => $request->orderstatus
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Order updated successfully!',
                'order'   => $order->load(['items.product', 'payment']),
            ]);
        } catch (Exception $e) {
            Log::error('Order update failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update order.',
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();

            $order = Order::with(['items', 'payment'])->findOrFail($id);

            // Only allow deletion of pending or cancelled orders
            if (!in_array($order->orderstatus, ['pending', 'cancelled'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete order with status: ' . $order->orderstatus,
                ], 400);
            }

            // Delete order items first
            foreach ($order->items as $item) {
                $item->delete();
            }

            // Delete payment if exists
            if ($order->payment) {
                $order->payment->delete();
            }

            // Delete order
            $order->delete();

            DB::commit();

            Log::info("Order deleted successfully", ['order_id' => $id]);

            return response()->json([
                'success' => true,
                'message' => 'Order deleted successfully!',
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Order deletion failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete order.',
            ], 500);
        }
    }

    /**
     * Generate unique order number
     */
    private function generateOrderNumber()
    {
        $prefix = 'ORD';
        $timestamp = now()->format('Ymd');
        $random = str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
        
        return $prefix . $timestamp . $random;
    }

    /**
     * Get order statistics for dashboard
     */
    public function getOrderStats()
    {
        try {
            $customerId = Auth::user()->customer->id;
            
            $stats = [
                'total_orders' => Order::where('customer_id', $customerId)->count(),
                'pending_orders' => Order::where('customer_id', $customerId)
                    ->where('orderstatus', 'pending')->count(),
                'completed_orders' => Order::where('customer_id', $customerId)
                    ->where('orderstatus', 'delivered')->count(),
                'total_spent' => Order::where('customer_id', $customerId)
                    ->sum('totalprice'),
            ];

            return response()->json(['success' => true, 'stats' => $stats]);
        } catch (Exception $e) {
            Log::error('Error fetching order stats: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Unable to fetch statistics']);
        }
    }
}