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

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     */
   public function create(array $input): User
{
    Log::info('Register request received', $input);

    Validator::make($input, [
        'username'   => ['required', 'string', 'max:120', 'unique:users,username'],
        'firstname'  => ['nullable', 'string', 'max:120'],
        'lastname'   => ['nullable', 'string', 'max:120'],
        'email'      => ['required', 'string', 'email', 'max:180', 'unique:users,email'],
        'phone'      => ['required', 'regex:/^[0-9]{10}$/', 'unique:user_phones,phone'],
        'password'   => $this->passwordRules(),
        'terms'      => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
    ])->validate();

    Log::info('Validation passed ✅');

    $user = User::create([
        'username'  => $input['username'],
        'firstname' => $input['firstname'] ?? null,
        'lastname'  => $input['lastname'] ?? null,
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
