<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\Customer;
use App\Models\UserPhone;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;


class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     */
    public function create(array $input): User
    {
        Log::info('Register request received', $input);

        // Base validation
        Validator::make($input, [
            'username'   => ['required', 'string', 'max:120'],
            'firstname'  => ['required', 'string', 'max:120'],
            'lastname'   => ['required', 'string', 'max:120'],
            'email'      => ['required', 'string', 'email', 'max:180'],
            'phone'      => ['required', 'regex:/^[0-9]{10}$/'],
            'password'   => ['required', 'string', 'min:6', 'confirmed'],
            'terms'      => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        // Manual duplicate check
        if (User::where('username', $input['username'])->exists()) {
            throw ValidationException::withMessages([
                'username' => 'Username already taken. Please choose another.',
            ]);
        }

        if (User::where('email', $input['email'])->exists()) {
            throw ValidationException::withMessages([
                'email' => 'Email already in use. Try another email.',
            ]);
        }

        if (UserPhone::where('phone', $input['phone'])->exists()) {
            throw ValidationException::withMessages([
                'phone' => 'Phone number already in use. Try another number.',
            ]);
        }

        Log::info('Validation passed ✅');

        // Create user
        $user = User::create([
            'username'  => $input['username'],
            'firstname' => $input['firstname'],
            'lastname'  => $input['lastname'],
            'email'     => $input['email'],
            'password'  => Hash::make($input['password']),
            'status'    => 'active',
            'usertype'  => $input['email'] === 'yashnick514@gmail.com' ? 'admin' : 'customer',
        ]);

        Log::info('User created ✅', ['id' => $user->id]);

        Customer::create(['user_id' => $user->id]);

        UserPhone::create([
            'user_id' => $user->id,
            'phone'   => $input['phone'],
        ]);

        Log::info('Customer + Phone created ✅');

        return $user;
    }
}
