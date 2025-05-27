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
         // Fetch random region, district, and ward
         $region = Region::inRandomOrder()->first();
         $district = $region ? $region->districts()->inRandomOrder()->first() : null;
         $ward = $district ? $district->wards()->inRandomOrder()->first() : null;
 
        return [
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'event_date' => fake()->dateTimeBetween('now', '+1 year')->format('Y-m-d'),
            'status' => fake()->randomElement(['pending', 'completed', 'cancelled']),
            'region_id' => $region ? $region->id : null,
            'district_id' => $district ? $district->id : null,
            'ward_id' => $ward ? $ward->id : null,
            'created_by' => User::whereIn('role', ['admin', 'hospital', 'organization'])
                ->inRandomOrder()
                ->first()
                ->id ?? User::factory()->create(['role' => fake()->randomElement(['admin', 'hospital', 'organization'])])->id,
        ];
    }
}
