<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BloodInventory>
 */
class BloodInventoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'hospital_id' => User::where('role', 'hospital')->inRandomOrder()->first()->id ?? User::factory()->create(['role' => 'hospital'])->id,
            'blood_type' => fake()->randomElement(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']),
            'quantity' => fake()->numberBetween(10, 100),
            'expiry_date' => now()->addDays(fake()->numberBetween(1, 365)),
            'source' => fake()->randomElement(['donation', 'purchase']),
        ];
    }
}
