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
        $expiry = fake()->optional()->dateTimeBetween('now', '+30 days');

        return [
            'hospital_id' => User::factory(), // or inject real hospital user ID
            'blood_type' => fake()->randomElement(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']),
            'quantity' => fake()->numberBetween(1, 10),
            'source' => fake()->randomElement(['donation', 'purchase']),
            'expiry_date' => $expiry ? $expiry->format('Y-m-d') : null,
        ];
    }
}
