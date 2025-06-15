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
            'lat' => fake()->latitude(-7, -6), // Example: around Dar es Salaam
            'lng' => fake()->longitude(39, 40),
            'address' => 'Mataa shungashunga, Dar es Salaam 16103, Tanzania',
            'name' => 'TonCheers Web Designer',
            'url' => 'https://maps.google.com/?cid=13159152526387155134',
            'district' => 'Kinondoni',
            'region' => 'Mwanza Region',
            'country' => 'Tanzania',
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
