<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Http\Resources\CustomerResource;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * List all customers with their relationships.
     */
    public function index()
    {
        return CustomerResource::collection(
            Customer::with(['user', 'addresses', 'cart', 'wishlist', 'orders', 'review'])->get()
        );
    }

    /**
     * Show a single customer.
     */
    public function show(Customer $customer)
    {
        return new CustomerResource(
            $customer->load(['user', 'addresses', 'cart', 'wishlist', 'orders', 'review'])
        );
    }

    /**
     * Create a new customer record.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id|unique:customers,user_id',
        ]);

        $customer = Customer::create($data);

        return new CustomerResource($customer->load('user'));
    }

    /**
     * Update an existing customer.
     */
    public function update(Request $request, Customer $customer)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id|unique:customers,user_id,' . $customer->id,
        ]);

        $customer->update($data);

        return new CustomerResource($customer->load('user'));
    }

    /**
     * Delete a customer.
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();
        return response()->noContent();
    }
}
