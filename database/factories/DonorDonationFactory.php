<?php

namespace Database\Factories;

use App\Models\DonorDonation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DonorDonation>
 */
class DonorDonationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DonorDonation::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'donor_id' => User::where('role', 'donor')->inRandomOrder()->first()->id ?? User::factory()->create(['role' => 'donor'])->id,
            'recipient_id' => User::where('role', 'recipient')->inRandomOrder()->first()->id ?? User::factory()->create(['role' => 'recipient'])->id,
            'hospital_id' => User::where('role', 'hospital')->inRandomOrder()->first()->id ?? User::factory()->create(['role' => 'hospital'])->id,
            'blood_type' => fake()->randomElement(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']),
            'quantity' => fake()->randomNumber(1), // Donor donation in pint
        ];
    }
}
