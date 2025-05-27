<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EventRegistration>
 */
class EventRegistrationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::where('role', 'donor')->inRandomOrder()->first()->id,
            'event_id' => Event::inRandomOrder()->first()->id,
            'status' => fake()->randomElement(['pending', 'confirmed', 'canceled']),
            'registered_at' => now(),
        ];
    }
}
