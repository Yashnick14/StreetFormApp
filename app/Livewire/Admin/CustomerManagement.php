<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Customer;

class CustomerManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;

    public $editingCustomer = null;
    public $username, $firstname, $lastname, $email, $phone, $status;
    public $showModal = false;

    public $selectedCustomer = null; // single selected customer ID

    protected $paginationTheme = 'tailwind';

    public function getCustomersProperty()
    {
        return Customer::with(['user', 'user.phones'])
            ->whereHas('user', function ($query) {
                $query->where('username', 'like', "%{$this->search}%")
                      ->orWhere('firstname', 'like', "%{$this->search}%")
                      ->orWhere('lastname', 'like', "%{$this->search}%")
                      ->orWhere('email', 'like', "%{$this->search}%");
            })
            ->paginate($this->perPage);
    }

    public function getSelectedCustomerModelProperty()
    {
        if (!$this->selectedCustomer) {
            return null;
        }
        return Customer::with('user')->find($this->selectedCustomer);
    }

    public function toggleSelectedStatus()
    {
        $customer = $this->selectedCustomerModel;
        if (!$customer) {
            return;
        }

        $newStatus = $customer->user->status === 'active' ? 'inactive' : 'active';
        $customer->user->update(['status' => $newStatus]);

        session()->flash('message', 'Customer status updated successfully.');
    }

    public function delete(Customer $customer)
    {
        $customer->user()->delete();
        $customer->delete();
        session()->flash('message', 'Customer deleted successfully.');
    }

    public function edit(Customer $customer)
    {
        $this->editingCustomer = $customer->id;
        $this->username   = $customer->user->username;
        $this->firstname  = $customer->user->firstname;
        $this->lastname   = $customer->user->lastname;
        $this->email      = $customer->user->email;
        $this->phone      = $customer->user->phones->first()->phone ?? '';
        $this->status     = $customer->user->status;

        $this->showModal = true;
    }

    public function save()
    {
        $customer = Customer::findOrFail($this->editingCustomer);
        $user = $customer->user;

        $this->validate([
            'username'  => 'required|string|max:120|unique:users,username,' . $user->id,
            'firstname' => 'nullable|string|max:120',
            'lastname'  => 'nullable|string|max:120',
            'email'     => 'required|email|unique:users,email,' . $user->id,
            'phone'     => 'nullable|string|max:20',
            'status'    => 'required|in:active,inactive',
        ]);

        $user->update([
            'username'  => $this->username,
            'firstname' => $this->firstname,
            'lastname'  => $this->lastname,
            'email'     => $this->email,
            'status'    => $this->status,
        ]);

        if ($this->phone) {
            $user->phones()->updateOrCreate(
                ['user_id' => $user->id],
                ['phone'   => $this->phone]
            );
        }

        $this->reset(['editingCustomer', 'username', 'firstname', 'lastname', 'email', 'phone', 'status', 'showModal']);
        session()->flash('message', 'Customer updated successfully.');
    }

    public function closeModal()
    {
        $this->reset(['showModal', 'editingCustomer', 'username', 'firstname', 'lastname', 'email', 'phone', 'status']);
    }

    public function render()
    {
        return view('livewire.admin.customer-management', [
            'customers' => $this->customers,
        ])->layout('layouts.admin');
    }
}
