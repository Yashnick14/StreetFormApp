<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Cart;
use App\Models\Product;

class StripeController extends Controller
{
    /**
     * Create Stripe Checkout Session
     */
    public function createCheckoutSession(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $items = $request->items ?? [];
        $lineItems = [];

        foreach ($items as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $item['name'],
                    ],
                    'unit_amount' => $item['price'] * 100,
                ],
                'quantity' => $item['quantity'],
            ];
        }

        $session = StripeSession::create([
            'payment_method_types' => ['card'],
            'mode' => 'payment',
            'line_items' => $lineItems,
            'success_url' => env('STRIPE_SUCCESS_URL') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'  => env('STRIPE_CANCEL_URL'),
            // ✅ Always cast to string for Mongo consistency
            'metadata' => [
                'order_id'    => (string) ($request->order_id ?? null),
                'customer_id' => auth()->check() ? (string) auth()->user()->customer->id : null,
            ],
        ]);

        return response()->json(['url' => $session->url]);
    }

    /**
     * Success page after payment
     */
    public function success()
    {
        return view('checkout.success');
    }

    /**
     * Cancel page if user cancels payment
     */
    public function cancel()
    {
        return view('checkout.cancel');
    }

    /**
     * Stripe Webhook Listener
     */
    public function webhook(Request $request)
    {
        $payload   = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $secret    = config('services.stripe.webhook_secret');

        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $secret);
        } catch (\Exception $e) {
            \Log::error("❌ Stripe webhook signature error: " . $e->getMessage());
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;

            $orderId    = $session->metadata->order_id ?? null;
            $customerId = $session->metadata->customer_id ?? null;

            if ($orderId) {
                $order = Order::find($orderId);

                if ($order) {
                    // ✅ Mark order as paid
                    $order->update(['orderstatus' => 'paid']);
                    \Log::info("✅ Order marked as paid", ['order_id' => (string) $order->_id]);

                    // ✅ Save payment
                    try {
                        $payment = Payment::create([
                            'order_id'      => (string) $order->_id,
                            'paymentmethod' => 'card',
                            'amount'        => $order->totalprice,
                            'paymentdate'   => now(),
                        ]);

                        \Log::info("✅ Payment saved", $payment->toArray());
                    } catch (\Exception $e) {
                        \Log::error("❌ Payment save failed: " . $e->getMessage(), [
                            'order_id' => (string) $order->_id
                        ]);
                    }

                    // ✅ Clear cart
                    if ($customerId) {
                        $cart = Cart::where('customer_id', (string) $customerId)->first();
                        if ($cart) {
                            // Delete all items first (optional, but safe)
                            $cart->items()->delete();
                            // Delete the parent cart
                            $cart->delete();

                            \Log::info("🛒 Cart & items fully cleared for customer", [
                                'customer_id' => (string) $customerId,
                                'cart_id'     => (string) $cart->_id
                            ]);
                        } else {
                            \Log::warning("⚠️ No cart found for customer", [
                                'customer_id' => (string) $customerId
                            ]);
                        }
                    }


                }
            }
        }

        return response()->json(['status' => 'success']);
    }
}
