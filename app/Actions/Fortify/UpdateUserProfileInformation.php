<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\UserPhone;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param  array<string, mixed>  $input
     */
    public function update(User $user, array $input): void
    {
        Validator::make($input, [
            'username'  => ['required', 'string', 'max:120', Rule::unique('users')->ignore($user->id)],
            'firstname' => ['nullable', 'string', 'max:120'],
            'lastname'  => ['nullable', 'string', 'max:120'],
            'email'     => ['required', 'email', 'max:180', Rule::unique('users')->ignore($user->id)],
            'phone'     => ['required', 'regex:/^[0-9]{10}$/'],
            'photo'     => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
        ])->validateWithBag('updateProfileInformation');

        // ✅ Profile photo
        if (isset($input['photo'])) {
            $user->updateProfilePhoto($input['photo']);
        }

        // ✅ If email changed and verification is required
        if ($input['email'] !== $user->email &&
            $user instanceof MustVerifyEmail) {
            $this->updateVerifiedUser($user, $input);
        } else {
            $user->forceFill([
                'username'  => $input['username'],
                'firstname' => $input['firstname'] ?? null,
                'lastname'  => $input['lastname'] ?? null,
                'email'     => $input['email'],
            ])->save();
        }

        // ✅ Save/update phone in user_phones table
        UserPhone::updateOrCreate(
            ['user_id' => $user->id],
            ['phone'   => $input['phone']]
        );
    }

    /**
     * Update the given verified user's profile information.
     *
     * @param  array<string, string>  $input
     */
    protected function updateVerifiedUser(User $user, array $input): void
    {
        $user->forceFill([
            'username'  => $input['username'],
            'firstname' => $input['firstname'] ?? null,
            'lastname'  => $input['lastname'] ?? null,
            'email'     => $input['email'],
            'email_verified_at' => null,
        ])->save();

        $user->sendEmailVerificationNotification();
    }
}
