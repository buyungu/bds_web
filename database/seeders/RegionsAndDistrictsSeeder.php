<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegionsAndDistrictsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $regions = [
            'Arusha', 'Dar es Salaam', 'Dodoma', 'Geita',
            'Iringa', 'Kagera', 'Kigoma', 'Kilimanjaro', 'Lindi',
            'Mara', 'Mbeya', 'Morogoro', 'Mtwara', 'Mwanza',
            'Njombe', 'Pemba North', 'Pemba South', 'Rukwa',
            'Ruvuma', 'Shinyanga', 'Simiyu', 'Singida',
            'Tabora', 'Tanga'
        ];

        // Insert regions into the database
        foreach ($regions as $region) {
            DB::table('regions')->insert([
                'name' => $region,
            ]);
        }

        // Districts data
        $districtsData = [
            'Arusha' => [
                'Arusha City', 'Arumeru', 'Babati', 'Karatu', 'Longido', 
                'Monduli', 'Ngorongoro'
            ],
            'Dar es Salaam' => [
                'Ilala', 'Kinondoni', 'Temeke'
            ],
            'Dodoma' => [
                'Dodoma Urban', 'Bahi', 'Chamwino', 'Chemba', 'Kondoa',
                'Kongwa', 'Mpwapwa'
            ],
            'Geita' => [
                'Geita Town', 'Bukombe', 'Chato', 'Nyang’hwale', 'Mbogwe'
            ],
            'Iringa' => [
                'Iringa City', 'Kilolo', 'Mufindi', 'Njombe', 'Ludewa', 'Makete'
            ],
            'Kagera' => [
                'Bukoba Town', 'Biharamulo', 'Chato', 'Karagwe', 'Muleba', 'Ngara'
            ],
            'Kigoma' => [
                'Kigoma Town', 'Buhigwe', 'Kasulu', 'Kibondo', 'Uvinza'
            ],
            'Kilimanjaro' => [
                'Moshi Town', 'Hai', 'Same', 'Rombo', 'Siha', 'Mwanga'
            ],
            'Lindi' => [
                'Lindi Town', 'Kilwa', 'Liwale', 'Nachingwea', 'Ruangwa'
            ],
            'Mara' => [
                'Musoma Town', 'Bunda', 'Tarime', 'Serengeti', 'Rorya', 'Butiama'
            ],
            'Mbeya' => [
                'Mbeya City', 'Mbozi', 'Ileje', 'Rungwe', 'Chunya', 'Mbeya'
            ],
            'Morogoro' => [
                'Morogoro City', 'Mvomero', 'Kilosa', 'Gairo', 'Ulanga', 'Mahenge'
            ],
            'Mtwara' => [
                'Mtwara Town', 'Newala', 'Nanyumbu', 'Masasi', 'Tandahimba'
            ],
            'Mwanza' => [
                'Nyamagana', 'Misungwi', 'Magu', 'Ilemela', 'Kwimba', 'Sengerema'
            ],
            'Njombe' => [
                'Njombe Town', 'Ludewa', 'Makete', 'Wanging’ombe', 'Ruaha'
            ],
            'Pemba North' => [
                'Wete', 'Micheweni', 'Chake Chake', 'Pemba South'
            ],
            'Pemba South' => [
                'Mkoani', 'Chake Chake', 'Wete'
            ],
            'Rukwa' => [
                'Sumbawanga', 'Nkasi', 'Kalambo', 'Sumbawanga Town'
            ],
            'Ruvuma' => [
                'Songea', 'Mbinga', 'Namtumbo', 'Tunduru', 'Nyasa'
            ],
            'Shinyanga' => [
                'Shinyanga City', 'Kahama', 'Maswa', 'Shinyanga District'
            ],
            'Simiyu' => [
                'Bariadi', 'Meatu', 'Itilima', 'Maswa'
            ],
            'Singida' => [
                'Singida Town', 'Ikungi', 'Mkalama', 'Manyoni', 'Singida District'
            ],
            'Tabora' => [
                'Tabora City', 'Urambo', 'Nzega', 'Igunga', 'Tabora Rural'
            ],
            'Tanga' => [
                'Tanga City', 'Muheza', 'Handeni', 'Pangani', 'Korogwe', 'Lushoto'
            ]
        ];

        // Insert districts for each region
        foreach ($districtsData as $regionName => $districts) {
            $regionId = DB::table('regions')->where('name', $regionName)->first()->id;

            foreach ($districts as $district) {
                DB::table('districts')->insert([
                    'name' => $district,
                    'region_id' => $regionId,
                ]);
            }
        }
    }
}
