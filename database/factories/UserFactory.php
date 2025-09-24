<?php

namespace Database\Factories;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Jetstream\Features;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $first = fake()->firstName();
        $last  = fake()->lastName();

        return [
            'username'              => Str::slug($first . '.' . $last) . fake()->unique()->numberBetween(100, 999),
            'firstname'             => $first,
            'lastname'              => $last,
            'email'                 => fake()->unique()->safeEmail(),
            'email_verified_at'     => now(),
            'password'              => static::$password ??= Hash::make('password'),
            'status'                => fake()->randomElement(['active', 'inactive']),
            'usertype'              => 'customer',   // default; can override for admin
            'two_factor_secret'     => null,
            'two_factor_recovery_codes' => null,
            'remember_token'        => Str::random(10),
            'profile_photo_path'    => null,
            'current_team_id'       => null,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Indicate that the user should have a personal team.
     */
    public function withPersonalTeam(?callable $callback = null): static
    {
        if (! Features::hasTeamFeatures()) {
            return $this->state([]);
        }

        return $this->has(
            Team::factory()
                ->state(fn (array $attributes, User $user) => [
                    'name'          => $user->username . '\'s Team',
                    'user_id'       => $user->id,
                    'personal_team' => true,
                ])
                ->when(is_callable($callback), $callback),
            'ownedTeams'
        );
    }
}
