<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Contracts\LoginResponse;
use Laravel\Fortify\Contracts\TwoFactorLoginResponse;
use Laravel\Fortify\Contracts\FailedLoginResponse;
use Laravel\Fortify\Contracts\RegisterResponse;
use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Models\User;

class FortifyServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // Registration & Profile
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        // Rate limiting
        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::lower($request->input(Fortify::username())) . '|' . $request->ip();
            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        // Login redirect with Sanctum + session handling
        $this->app->singleton(\Laravel\Fortify\Contracts\LoginResponse::class, function () {
            return new class implements \Laravel\Fortify\Contracts\LoginResponse {
                public function toResponse($request)
                {
                    // Clear old session token
                    $request->session()->forget(['api_token']);

                    // Always use fresh user record
                    $user = \Illuminate\Support\Facades\Auth::user()->fresh();

                    // Delete all old Sanctum tokens
                    $user->tokens()->delete();

                    // Create a fresh Sanctum token
                    $token = $user->createToken('web_login')->plainTextToken;

                    // Store new token in session
                    $request->session()->put('api_token', $token);

                    // If request is API, return JSON
                    if ($request->wantsJson()) {
                        return response()->json([
                            'user'  => $user,
                            'token' => $token,
                        ]);
                    }

                    // Redirect based on usertype
                    return $user->usertype === 'admin'
                        ? redirect()->route('admin.dashboard')
                        : redirect()->route('home');
                }
            };
        });


        // 2FA login redirect
        $this->app->singleton(TwoFactorLoginResponse::class, function () {
            return new class implements TwoFactorLoginResponse {
                public function toResponse($request)
                {
                    $user = Auth::user()->fresh();
                    $token = $user->createToken('2fa_login')->plainTextToken;
                    $request->session()->put('api_token', $token);

                    if ($request->wantsJson()) {
                        return response()->json(['user' => $user, 'token' => $token]);
                    }

                    return $user->usertype === 'admin'
                        ? redirect()->intended(route('admin.dashboard'))
                        : redirect()->intended(route('home'));
                }
            };
        });

        // Redirect after register → Login page with message
        $this->app->singleton(RegisterResponse::class, function () {
            return new class implements RegisterResponse {
                public function toResponse($request)
                {
                    return redirect()->route('login')
                        ->with('success', 'Account created successfully! Please login.');
                }
            };
        });

        // Failed login response
        $this->app->singleton(FailedLoginResponse::class, function () {
            return new class implements FailedLoginResponse {
                public function toResponse($request)
                {
                    return back()
                        ->withInput($request->only('email', 'remember'))
                        ->with('error', 'Invalid email or password.');
                }
            };
        });

        Fortify::authenticateUsing(function ($request) {
            $user = User::where('email', $request->email)->first();

            // No user found
            if (!$user) {
                return null; // default invalid credentials
            }

            // Wrong password
            if (!Hash::check($request->password, $user->password)) {
                return null; // default invalid credentials
            }

            // Inactive account
            if ($user->status === 'inactive') {
                throw ValidationException::withMessages([
                    'email' => ['Account inactive. Please contact support.'],
                ]);
            }

            return $user; // Success
        });
    }
}