<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Http\Resources\AdminResource;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Show the single admin (since only 1 is allowed).
     */
    public function index()
    {
        $admin = Admin::with('user')->first();
        return $admin ? new AdminResource($admin) : response()->json(['message' => 'No admin found'], 404);
    }

    /**
     * Create the only admin record.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id|unique:admins,user_id',
        ]);

        // Will throw exception if admin already exists (see model booted())
        $admin = Admin::create($data);

        return new AdminResource($admin->load('user'));
    }

    /**
     * Update the admin (rare, usually to change the linked user).
     */
    public function update(Request $request, Admin $admin)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id|unique:admins,user_id,' . $admin->id,
        ]);

        $admin->update($data);

        return new AdminResource($admin->load('user'));
    }

    /**
     * Delete the admin (not typical, but allowed).
     */
    public function destroy(Admin $admin)
    {
        $admin->delete();
        return response()->noContent();
    }
}
