<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;

class OrderConfirmation extends Component
{
    public $orderId;
    public $order;
    public $items = [];

    public function mount($orderId)
    {
        // Load the order from MongoDB without relationships - same as OrderManagement
        $this->order = Order::findOrFail($orderId);

        // Get items from MongoDB without relationships - same as OrderManagement  
        $this->items = OrderItem::where('order_id', (string) $this->order->_id)->get();

        // Don't try to access relationships here - avoid the prepare() on null error
    }

    public function render()
    {
        // Load products separately (from MySQL) - same pattern as OrderManagement
        $productIds = $this->items->pluck('product_id')->unique()->filter();
        
        $products = collect();
        if ($productIds->isNotEmpty()) {
            $products = Product::whereIn('id', $productIds)->get()->keyBy('id');
        }

        // Don't try to set properties on MongoDB models - pass products separately
        return view('livewire.customer.order-confirmation', [
            'order' => $this->order,
            'items' => $this->items,
            'products' => $products,
        ])->layout('layouts.app');
    }
}