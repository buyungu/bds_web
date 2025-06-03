<?php

namespace Database\Seeders;

use App\Models\BloodInventory;
use App\Models\BloodRequest;
use App\Models\BloodStock;
use App\Models\DonorDonation;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\Region;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create 20 demo users
        $users = User::factory()->count(5)->create();

        // // Optionally: Create one admin with known credentials
        // User::factory()->create([
        //     'name' => 'Daud Benjamini',
        //     'email' => 'daudbuyungu@gmail.com',
        //     'password' => bcrypt('1234'),
        //     'role' => 'admin',
        //     'location' => [
        //         'lat' => -6.712, // Example: around Dar es Salaam
        //         'lng' => 7.732,
        //         'address' => 'Mataa shungashunga, Dar es Salaam 16103, Tanzania',
        //         'name' => 'TonCheers Web Designer',
        //         'url' => 'https://maps.google.com/?cid=13159152526387155134',
        //         'district' => 'Kinondoni',
        //         'region' => 'Dar es Salaam',
        //         'country' => 'Tanzania',
        //      ],
        // ]);

        $hospitals = User::where('role', 'hospital')->get();
        // Create 20 events, randomly linked to those users
        Event::factory()->count(20)->make()->each(function ($event) use ($hospitals) {
            $event->created_by = $hospitals->random()->id;
            $event->save();
        });
        
        $hospitals = User::where('role', 'hospital')->get();
        $recipients = User::where('role', 'user')->get();

        if ($hospitals->isEmpty() || $recipients->isEmpty()) {
            $this->command->warn('You need users with roles hospital and user to seed blood requests.');
            return;
        }

        BloodRequest::factory()->count(20)->make()->each(function ($request) use ($hospitals, $recipients) {
            $request->hospital_id = $hospitals->random()->id;
            $request->recipient_id = $recipients->random()->id;
            $request->save();
        });

        // Create 20 blood inventories, randomly linked to hospitals
        $hospitals = User::where('role', 'hospital')->get();

        if ($hospitals->isEmpty()) {
            $this->command->warn('No hospitals found to seed blood inventory.');
            return;
        }

        foreach ($hospitals as $hospital) {
            BloodInventory::factory()->count(10)->create([
                'hospital_id' => $hospital->id,
            ]);
        }
        
    }
}
