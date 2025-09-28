<?php

namespace App\Livewire\Components;

use Livewire\Component;
use App\Models\Order;

class OrderStepper extends Component
{
    public $orderId;
    public $order;

    public function mount($orderId)
    {
        $this->orderId = $orderId;
        $this->loadOrder();
    }

    public function loadOrder()
    {
        $this->order = Order::find($this->orderId);
    }

    protected $listeners = ['refreshStepper' => 'loadOrder'];

    public function render()
    {
        return view('livewire.components.order-stepper');
    }
}
