<?php

namespace Database\Factories;

use App\Models\Region;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
        // Fetch random region, district, and ward
        $region = Region::inRandomOrder()->first();
        $district = $region ? $region->districts()->inRandomOrder()->first() : null;
        $ward = $district ? $district->wards()->inRandomOrder()->first() : null;

        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('1234'),
            'region_id' => $region ? $region->id : null,
            'district_id' => $district ? $district->id : null,
            'ward_id' => $ward ? $ward->id : null,            
            'role' => fake()->randomElement(['donor', 'recipient', 'hospital', 'organization', 'admin']),
            'blood_type'=> fake()->randomElement(['A+','A-','B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']),
            'remember_token' => Str::random(10),
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
}
