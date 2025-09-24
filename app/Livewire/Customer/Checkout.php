<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use App\Models\Cart as CartModel;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;

class Checkout extends Component
{
    public $cart;
    public $items = [];
    public $firstname = '';
    public $lastname = '';
    public $email = '';
    public $phone = '';
    public $house_number = '';
    public $street = '';
    public $city = '';
    public $postal_code = '';
    public $payment_method = 'cod';
    public $total = 0;

    public $showConfirmModal = false;

    public function mount()
    {
        $this->loadCart();

        $user = Auth::user();
        if ($user) {
            $this->firstname = $user->firstname ?? '';
            $this->lastname  = $user->lastname ?? '';
            $this->email     = $user->email ?? '';
            $this->phone     = $user->phones()->first()->phone ?? '';
        }
    }

    public function loadCart()
    {
        $this->cart = CartModel::where('customer_id', (string) Auth::id())->first();
        $this->items = $this->cart ? $this->cart->items : [];

        $this->total = 0;
        foreach ($this->items as $item) {
            // ✅ Always attach product details for UI & confirmation modal
            $item->product = Product::find($item->product_id);
            if ($item->product) {
                $this->total += $item->quantity * $item->unitprice;
            }
        }
    }

    public function placeOrder()
    {
        if (!$this->house_number || !$this->street || !$this->city || !$this->postal_code) {
            $this->dispatch('toast', type: 'error', message: 'Please complete your address.');
            return;
        }

        if (count($this->items) === 0) {
            $this->dispatch('toast', type: 'error', message: 'Your cart is empty.');
            return;
        }

        if ($this->payment_method === 'cod') {
                // ✅ Re-attach product details to items for confirmation modal
                foreach ($this->items as $item) {
                    $item->product = Product::find($item->product_id);
                }

                $this->showConfirmModal = true;
                return;
            }

        // ✅ Card → create pending order then redirect to Stripe
        if ($this->payment_method === 'card') {
            $cartItems = [];
            foreach ($this->items as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    $cartItems[] = [
                        'product_id' => $product->id,
                        'name'       => $product->name,
                        'price'      => $item->unitprice,
                        'quantity'   => $item->quantity,
                        'size'       => $item->size,
                    ];
                }
            }

            if (empty($cartItems)) {
                $this->dispatch('toast', type: 'error', message: 'No valid products in cart.');
                return;
            }

            // 🔹 Create pending order
            $order = Order::create([
                'customer_id'    => (string) Auth::user()->customer->id,
                'firstname'      => $this->firstname,
                'lastname'       => $this->lastname,
                'email'          => $this->email,
                'phone'          => $this->phone,
                'house_number'   => $this->house_number,
                'street'         => $this->street,
                'city'           => $this->city,
                'postal_code'    => $this->postal_code,
                'payment_method' => 'card',
                'orderdate'      => now(),
                'orderstatus'    => 'pending',
                'totalprice'     => $this->total,
            ]);

            // 🔹 Save order items immediately
            foreach ($cartItems as $ci) {
                OrderItem::create([
                    'order_id'      => (string) $order->_id,
                    'product_id'    => $ci['product_id'],
                    'orderquantity' => $ci['quantity'],
                    'ordersize'     => $ci['size'],
                    'orderprice'    => $ci['price'],
                ]);
            }

            // 🔹 Send to Stripe
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

            $lineItems = [];
            foreach ($cartItems as $ci) {
                $lineItems[] = [
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => ['name' => $ci['name']],
                        'unit_amount' => $ci['price'] * 100,
                    ],
                    'quantity' => $ci['quantity'],
                ];
            }

            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'mode' => 'payment',
                'line_items' => $lineItems,
                'success_url' => env('STRIPE_SUCCESS_URL'),
                'cancel_url'  => env('STRIPE_CANCEL_URL'),
                'metadata' => [
                    'order_id'    => (string) $order->_id,
                    'customer_id' => (string) Auth::id(),
                ],
            ]);

            return redirect()->away($session->url);
        }
    }

public function saveOrder()
{
    try {
        $order = Order::create([
            'customer_id'    => (string) Auth::user()->customer->id,
            'firstname'      => $this->firstname,
            'lastname'       => $this->lastname,
            'email'          => $this->email,
            'phone'          => $this->phone,
            'house_number'   => $this->house_number,
            'street'         => $this->street,
            'city'           => $this->city,
            'postal_code'    => $this->postal_code,
            'payment_method' => 'cod',
            'orderdate'      => now(),
            'orderstatus'    => 'pending',
            'totalprice'     => $this->total,
        ]);

        foreach ($this->items as $item) {
            $product = Product::find($item->product_id);
            if ($product) {
                OrderItem::create([
                    'order_id'      => (string) $order->_id,
                    'product_id'    => $product->id,
                    'orderquantity' => $item->quantity,
                    'ordersize'     => $item->size,
                    'orderprice'    => $item->unitprice,
                ]);
            }
        }

        Payment::create([
            'order_id'      => (string) $order->_id,
            'paymentmethod' => 'cod',
            'amount'        => $this->total,
            'paymentdate'   => now(),
        ]);

        // Clear cart completely
        $cart = CartModel::where('customer_id', (string) Auth::id())->first();
        if ($cart) {
            $cart->items()->delete();
            $cart->delete();
        }

        $this->showConfirmModal = false;
        $this->dispatch('toast', type: 'success', message: 'Order placed successfully!');
        return redirect()->route('home');

    } catch (\Exception $e) {
        \Log::error('Order save failed: '.$e->getMessage());
        $this->dispatch('toast', type: 'error', message: 'Failed to save order.');
    }
}


    public function render()
    {
        return view('livewire.customer.checkout', [
            'items' => $this->items,
            'total' => $this->total
        ])->layout('layouts.app');
    }
}
