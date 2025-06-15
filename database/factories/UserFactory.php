<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        // Simulated location structure matching your description
        $location = [
            'lat' => fake()->latitude(-7, -6), // Example: around Dar es Salaam
            'lng' => fake()->longitude(39, 40),
            'address' => 'Mataa shungashunga, Dar es Salaam 16103, Tanzania',
            'name' => 'Mabibo Hospital',
            'url' => 'https://maps.google.com/?cid=13159152526387155134',
            'district' => 'Ubungo',
            'region' => 'Mwanza Region',
            'country' => 'Tanzania',
        ];

        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('1234'),
            'phone' => fake()->phoneNumber(),
            'location' => $location,
            'role' => fake()->randomElement(['user', 'hospital',]),
            'blood_type' => fake()->randomElement(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']),
            'remember_token' => Str::random(10),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
