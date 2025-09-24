<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * List all users with relationships.
     */
    public function index()
    {
        return UserResource::collection(
            User::with(['phones', 'admin', 'customer'])->get()
        );
    }

    /**
     * Show a single user.
     */
    public function show(User $user)
    {
        return new UserResource(
            $user->load(['phones', 'admin', 'customer'])
        );
    }

    /**
     * Create a new user.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'username'  => 'required|string|max:120|unique:users',
            'firstname' => 'nullable|string|max:120',
            'lastname'  => 'nullable|string|max:120',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|string|min:6',
            'status'    => 'in:active,inactive',
            'usertype'  => 'in:admin,customer',
        ]);

        $data['password'] = bcrypt($data['password']); // hash password

        $user = User::create($data);

        return new UserResource($user->load(['phones', 'admin', 'customer']));
    }

    /**
     * Update an existing user.
     */
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'firstname' => 'nullable|string|max:120',
            'lastname'  => 'nullable|string|max:120',
            'status'    => 'in:active,inactive',
            'usertype'  => 'in:admin,customer',
        ]);

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        return new UserResource($user->load(['phones', 'admin', 'customer']));
    }

    /**
     * Delete a user.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->noContent();
    }
}
