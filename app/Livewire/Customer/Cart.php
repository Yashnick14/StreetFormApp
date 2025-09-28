<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use App\Models\Cart as CartModel;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class Cart extends Component
{
    public $cart;
    public $items = [];

    // Listen for events from product page
    protected $listeners = ['addToCart'];

    public function mount()
    {
        $this->loadCart();
    }

    public function loadCart()
    {
        $this->cart = CartModel::where('customer_id', (string)Auth::id())->first();
        $this->items = $this->cart ? $this->cart->items : [];

        // Attach product from MySQL manually & ensure quantity is synced
        foreach ($this->items as $item) {
            $item->product = Product::find($item->product_id);

            // Ensure Livewire knows about quantity
            $item->quantity = (int) $item->quantity;
        }
    }


    public function addToCart($productId, $size = null, $quantity = 1)
    {
        $product = Product::findOrFail($productId);

        $cart = CartModel::firstOrCreate([
            'customer_id' => (string)Auth::id(),
        ]);

        $item = $cart->items()
            ->where('product_id', $productId)
            ->where('size', $size)
            ->first();

        if ($item) {
            $item->quantity += $quantity;
            $item->save();
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'size'       => $size,
                'quantity'   => $quantity,
                'unitprice'  => $product->price,
            ]);
        }

        $this->loadCart();

        // ✅ Livewire v3 dispatch
        $this->dispatch('toast', type: 'success', message: 'Product added to cart!');
    }

    public function updateQuantity($itemId, $quantity)
    {
        $quantity = (int) $quantity;

        if ($quantity <= 0) {
            $this->dispatch('toast', type: 'error', message: 'Quantity must be at least 1.');
            return;
        }

        $item = CartItem::find($itemId);

        if ($item) {
            // ✅ check stock limit
            $product = Product::find($item->product_id);
            if ($product && $item->size) {
                $availableStock = $product->stockquantity[$item->size] ?? 0;
                if ($quantity > $availableStock) {
                    $this->dispatch('toast', type: 'error', message: 'Not enough stock available.');
                    return;
                }
            }

            $item->quantity = $quantity;
            $item->save();
            $this->loadCart();

            $this->dispatch('toast', type: 'success', message: 'Quantity updated.');
        }
    }


    public function removeItem($itemId)
    {
        $item = CartItem::find($itemId);

        if ($item) {
            $item->delete();
            $this->loadCart();

            $this->dispatch('toast', type: 'success', message: 'Item removed from cart.');
        } else {
            $this->dispatch('toast', type: 'error', message: 'Cart item not found.');
        }
    }

    public function render()
    {
        return view('livewire.customer.cart')
            ->layout('layouts.app');
    }
}
