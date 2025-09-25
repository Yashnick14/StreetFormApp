<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Order;
use Illuminate\Pagination\LengthAwarePaginator;

class OrderManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $sortBy = 'orderdate';
    public $sortDirection = 'desc';
    public $statusUpdates = [];
    
    // Add these missing properties
    public $showStatusModal = false;
    public $selectedOrderId = null;

    protected $queryString = ['search', 'sortBy', 'sortDirection'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        $this->sortDirection = $this->sortBy === $field && $this->sortDirection === 'asc'
            ? 'desc'
            : 'asc';
        $this->sortBy = $field;
    }

    // Add these missing methods
    public function openStatusModal($orderId)
    {
        $this->selectedOrderId = $orderId;
        $this->showStatusModal = true;
    }

    public function updateStatus($orderId)
    {
        if (isset($this->statusUpdates[$orderId]) && $this->statusUpdates[$orderId] !== '') {
            $status = $this->statusUpdates[$orderId];

            $order = Order::find($orderId);
            if ($order) {
                $order->update(['orderstatus' => $status]);
            }

            // Reset just this dropdown so it always shows "Status"
            $this->statusUpdates[$orderId] = '';

            session()->flash('message', 'Order status updated successfully!');
        }
    }


    public function render()
    {
        // Remove the eager loading that was causing issues
        $query = Order::query();

        if ($this->search) {
            $s = strtolower(trim($this->search));
            $query->where(function ($q) use ($s) {
                $q->orWhere('firstname', 'like', "%{$s}%")
                  ->orWhere('lastname', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%")
                  ->orWhere('phone', 'like', "%{$s}%")
                  ->orWhere('orderstatus', 'like', "%{$s}%");
            });
        }

        // Get orders without relationships to avoid SQL issues
        $allOrders = $query
            ->orderBy($this->sortBy, $this->sortDirection)
            ->get();

        // Manual pagination
        $page = $this->page ?? 1;
        $perPage = 10;
        $paged = $allOrders->forPage($page, $perPage);
        $orders = new LengthAwarePaginator(
            $paged,
            $allOrders->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('livewire.admin.order-management', compact('orders'))
            ->layout('layouts.admin');
    }
}