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
        // Seed only for Region ID = 1
        $this->call([
            RegionsAndDistrictsSeeder::class,
            WardsSeeder::class,
        ]);

        // Get Region with ID 1
        $region = Region::find(3);
        if (!$region) {
            $this->command->error("Region with ID 1 not found!");
            return;
        }

        // Get all districts in the region
        $districts = $region->districts;

        // Use first district and ward for admin
        $firstDistrict = $districts->first();
        $firstWard = $firstDistrict->wards->first();

        // // Create admin
        // $admin = User::create([
        //     'name' => 'Daud Benjamini',
        //     'email' => 'daudbuyungu@gmail.com',
        //     'password' => bcrypt('1234'),
        //     'role' => 'admin',
        //     'region_id' => $region->id,
        //     'district_id' => $firstDistrict->id,
        //     'ward_id' => $firstWard->id
        // ]);

        // Loop through each district
        foreach ($districts as $district) {
            // Get all wards in this district
            $wards = $district->wards;

            foreach ($wards as $ward) {
                // Create Users for this ward
                $donors = User::factory(2)->create([
                    'role' => 'donor',
                    'ward_id' => $ward->id,
                    'district_id' => $district->id,
                    'region_id' => $region->id,
                    'password' => bcrypt('1234'),
                ]);

                $recipients = User::factory(2)->create([
                    'role' => 'recipient',
                    'ward_id' => $ward->id,
                    'district_id' => $district->id,
                    'region_id' => $region->id,
                    'password' => bcrypt('1234'),
                ]);

                $hospitals = User::factory(2)->create([
                    'role' => 'hospital',
                    'ward_id' => $ward->id,
                    'district_id' => $district->id,
                    'region_id' => $region->id,
                    'password' => bcrypt('1234'),
                ]);

                $organizations = User::factory(1)->create([
                    'role' => 'organization',
                    'ward_id' => $ward->id,
                    'district_id' => $district->id,
                    'region_id' => $region->id,
                    'password' => bcrypt('1234'),
                ]);

                // Create events (admin, hospitals, organizations)
                $eventCreators = collect($hospitals)->merge($organizations);
                foreach ($eventCreators as $creator) {
                    foreach (range(1, 4) as $i) {
                        Event::factory()->create([
                            'created_by' => $creator->id,
                            'ward_id' => $ward->id,
                            'district_id' => $district->id,
                            'region_id' => $region->id,
                        ]);
                    }
                }

                // Register donors for events in the same ward
                foreach (Event::where('ward_id', $ward->id)->get() as $event) {
                    $donors->random(min(5, $donors->count()))->each(function ($donor) use ($event) {
                        EventRegistration::create([
                            'event_id' => $event->id,
                            'user_id' => $donor->id
                        ]);
                    });
                }

                // -------------------------
                // BLOOD DONATION & STOCK MANAGEMENT
                // -------------------------

                // // 1️⃣ **Donor Donations (direct donor-to-recipient)**
                // foreach ($donors as $donor) {
                //     if ($recipients->isNotEmpty() && $hospitals->isNotEmpty()) {
                //         DonorDonation::factory()->create([
                //             'donor_id' => $donor->id,
                //             'recipient_id' => $recipients->random()->id,
                //             'hospital_id' => $hospitals->random()->id
                //         ]);
                //     }
                // }

                // 2️⃣ **Blood Inventory (blood added to hospital stock)**
                foreach ($hospitals as $hospital) {
                    BloodInventory::factory(5)->create([
                        'hospital_id' => $hospital->id
                    ]);
                }

                // 3️⃣ **Blood Stock (actual hospital blood levels)**
                foreach ($hospitals as $hospital) {
                    $inventory = BloodInventory::where('hospital_id', $hospital->id)->get();

                    // Group inventory by blood type
                    $groupedInventory = $inventory->groupBy('blood_type');

                    foreach ($groupedInventory as $bloodType => $bloodBatches) {
                        BloodStock::create([
                            'hospital_id' => $hospital->id,
                            'blood_type' => $bloodType,
                            'quantity' => $bloodBatches->sum('quantity'),
                        ]);
                    }
                }

                // 4️⃣ **Blood Requests (recipients requesting blood from hospitals)**
                foreach ($recipients as $recipient) {
                    if ($hospitals->isNotEmpty()) {
                        BloodRequest::factory(3)->create([
                            'recipient_id' => $recipient->id,
                            'hospital_id' => $hospitals->random()->id
                        ]);
                    }
                }
            }
        }
    }
}
