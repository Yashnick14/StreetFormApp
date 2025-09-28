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

    public $statusUpdates = [];       // hold status changes temporarily
    public $showStatusModal = false;  // toggle modal
    public $selectedOrderId = null;   // store order being edited

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

    /**
     * Open modal and preload status
     */
    public function openStatusModal($orderId)
    {
        $this->selectedOrderId = $orderId;

        $order = Order::find($orderId);
        if ($order) {
            $this->statusUpdates[$orderId] = $order->orderstatus;
        }

        $this->showStatusModal = true;
    }

    /**
     * Change status and close modal
     */
public function changeStatus()
{
    if (!$this->selectedOrderId) return;

    $newStatus = $this->statusUpdates[$this->selectedOrderId] ?? null;

    if ($newStatus) {
        $order = Order::find($this->selectedOrderId);
        if ($order) {
            $current = $order->orderstatus;

            // Allowed transitions
            $flow = [
                'pending' => ['confirmed', 'cancelled'],
                'confirmed' => ['out for delivery', 'cancelled'],
                'out for delivery' => ['delivered', 'cancelled'],
                'delivered' => [],      // final state
                'cancelled' => []       // final state
            ];

            // Block invalid transitions
            if (!in_array($newStatus, $flow[$current] ?? [])) {
                session()->flash('error', " Cannot change status from {$current} to {$newStatus}.");
                $this->showStatusModal = false;
                return;
            }

            // Update valid transition
            $order->update(['orderstatus' => $newStatus]);

            session()->flash('message', " Order #{$this->selectedOrderId} status updated to {$newStatus}.");
        }
    }

    $this->showStatusModal = false;
}


    public function editSelected()
    {
        if (!$this->selectedOrderId) return;

        $order = Order::find($this->selectedOrderId);
        if ($order) {
            $this->statusUpdates[$this->selectedOrderId] = $order->orderstatus;
            $this->showStatusModal = true;
        }
    }


    public function render()
    {
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
