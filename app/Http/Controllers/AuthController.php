<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Customer;
use App\Models\UserPhone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    /**
     * Register via API
     */
    public function register(Request $request)
    {
        $request->validate([
            'username'  => 'required|string|max:120|unique:users,username',
            'firstname' => 'required|string|max:120',
            'lastname'  => 'required|string|max:120',
            'email'     => 'required|string|email|max:180|unique:users,email',
            'phone'     => 'required|regex:/^[0-9]{10}$/|unique:user_phones,phone',
            'password'  => 'required|string|min:6',
            'usertype'  => ['required', Rule::in(['customer','admin'])],
        ]);

        // prevent more than one admin
        if ($request->usertype === 'admin' && User::where('usertype', 'admin')->exists()) {
            return response()->json(['message' => 'An admin already exists.'], 422);
        }

        // create user
        $user = User::create([
            'username'  => $request->username,
            'firstname' => $request->firstname,
            'lastname'  => $request->lastname,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'status'    => 'active',
            'usertype'  => $request->usertype,
        ]);

        // attach customer profile
        if ($user->usertype === 'customer') {
            Customer::create(['user_id' => $user->id]);
        }
        
        // Manual duplicate check
        $existingUser = User::where('username', $input['username'])
            ->orWhere('email', $input['email'])
            ->orWhereHas('phones', function ($q) use ($input) {
                $q->where('phone', $input['phone']);
            })
            ->first();

        if ($existingUser) {
            throw ValidationException::withMessages([
                'email' => ' User already exists. Please login.',
            ]);
        }

        // store phone
        UserPhone::create([
            'user_id' => $user->id,
            'phone'   => $request->phone,
        ]);

        // token
        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'user'  => $user,
            'token' => $token,
        ]);
    }

    /**
     * Login via API
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Invalid login'], 401);
        }

        $user  = Auth::user();

        // Block inactive users
        if ($user->status === 'inactive') {
            Auth::logout();

            return back()->with('error', ' Account inactive. Please contact support.')
                        ->withInput();
        }
        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'user'  => $user,
            'token' => $token,
        ]);
    }

    /**
     * Logout (revoke tokens)
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out']);
    }
}
