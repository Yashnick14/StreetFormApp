<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;

class AdminDashboard extends Component
{
    public $totalUsers;
    public $totalProducts;
    public $totalOrders;
    public $revenue;

    public function mount()
    {
        $this->totalUsers = User::count();
        $this->totalProducts = Product::count();
        $this->totalOrders = Order::count();
        $this->revenue = Order::sum('totalprice'); // assuming `total` column in orders
    }

    public function render()
    {
        return view('livewire.admin.admin-dashboard')
            ->layout('layouts.admin'); // or use layouts.admin if you have one
    }
}
