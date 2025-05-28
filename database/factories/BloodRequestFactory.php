<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BloodRequest>
 */
class BloodRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'recipient_id' => User::factory(),
            'hospital_id' => User::factory(),
            'blood_type' => fake()->randomElement(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']),
            'quantity' => fake()->numberBetween(1, 5), // e.g. units of blood
            'status' => fake()->randomElement(['pending', 'partially matched', 'matched', 'fulfilled', 'canceled']),
            'urgency' => fake()->randomElement(['emergence', 'high', 'medium', 'low']),
        ];
    }
}
