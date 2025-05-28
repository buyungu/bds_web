<?php

namespace Database\Factories;

use App\Models\Region;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Simulated location structure matching your description
        $location = [
            'latitude' => fake()->latitude(),
            'longitude' => fake()->longitude(),
            'latitude' => fake()->latitude(), // Simulated altitude in meters
            'address' => fake()->streetAddress(),
            'district' => fake()->city(),
            'region' => fake()->state(),
        ];

        return [
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'event_date' => fake()->dateTimeBetween('now', '+1 year')->format('Y-m-d'),
            'status' => fake()->randomElement(['pending', 'completed', 'cancelled']),
            'location' => $location,
            'created_by' => User::whereIn('role', ['admin', 'hospital', 'organization'])
                ->inRandomOrder()
                ->first()
                ->id ?? User::factory()->create(['role' => fake()->randomElement(['admin', 'hospital', 'organization'])])->id,
        ];
    }
}
