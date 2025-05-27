<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WardsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $wardsData = [

            // Define wards for each district in Arusha region

            'Arusha City' => [
                'Baraa', 'Themi', 'Kimandolu', 'Kaloleni', 'Sekei', 'Sokon I', 'Lemara'
            ],
            'Arumeru' => [
                'Usa River', 'Nkoaranga', 'Ngaramtoni', 'Maji ya Chai', 'King’ori'
            ],
            'Babati' => [
                'Bagara', 'Bashnet', 'Mamire', 'Sigino', 'Gallapo'
            ],
            'Karatu' => [
                'Karatu', 'Rhotia', 'Endabash', 'Mang’ola', 'Qurus'
            ],
            'Longido' => [
                'Longido', 'Namanga', 'Engarenaibor', 'Mundarara'
            ],
            'Monduli' => [
                'Monduli Juu', 'Selela', 'Engaruka', 'Mto wa Mbu', 'Esilalei'
            ],
            'Ngorongoro' => [
                'Loliondo', 'Sale', 'Soitsambu', 'Oloipiri'
            ],


            // Define wards for each district in Dar es Salaam region

            'Ilala' => [
                'Gerezani', 'Kivukoni', 'Kariakoo', 'Mchikichini', 'Buguruni', 'Tabata', 'Segerea', 'Ukonga'
            ],
            'Kinondoni' => [
                'Magomeni', 'Makumbusho', 'Mwananyamala', 'Msasani', 'Kawe', 'Kinondoni', 'Oysterbay'
            ],
            'Temeke' => [
                'Chang’ombe', 'Keko', 'Mtoni', 'Tandika', 'Buza', 'Kurasini', 'Sandali'
            ],


            // Define wards for each district in Dodoma region

            'Dodoma Urban' => [
                'Kiwanja cha Ndege', 'Kizota', 'Madukani', 'Majengo', 'Miyuji', 'Nkuhungu'
            ],
            'Bahi' => [
                'Bahi', 'Chibelela', 'Chikola', 'Ibihwa', 'Ilindi'
            ],
            'Chamwino' => [
                'Chamwino', 'Buigiri', 'Dabalo', 'Idifu', 'Manchali'
            ],
            'Chemba' => [
                'Chemba', 'Churuku', 'Farkwa', 'Jangalo', 'Mrijo'
            ],
            'Kondoa' => [
                'Kondoa Mjini', 'Bolisa', 'Bumbuta', 'Kinyasi', 'Serya'
            ],
            'Kongwa' => [
                'Kongwa', 'Chitego', 'Lenjulu', 'Mkoka', 'Ugogoni'
            ],
            'Mpwapwa' => [
                'Mpwapwa Mjini', 'Chunyu', 'Gulwe', 'Lupeta', 'Wotta'
            ],


            // Define wards for each district in Geita region

            'Geita Town' => [
                'Bombambili', 'Bung’wangoko', 'Kalangalala', 'Mtakuja', 'Nyankumbu'
            ],
            'Bukombe' => [
                'Bugelenga', 'Busonzo', 'Igulwa', 'Uyovu', 'Ushirombo'
            ],
            'Chato' => [
                'Buseresere', 'Bwanga', 'Kakora', 'Nyamirembe', 'Chato Mjini'
            ],
            'Nyang’hwale' => [
                'Busolwa', 'Izunya', 'Kharumwa', 'Nyabulanda', 'Nyijundu'
            ],
            'Mbogwe' => [
                'Ikobe', 'Iponya', 'Luhalahu', 'Masumbwe', 'Ngemo'
            ],


            // Define wards for each district in Iringa region
            
            'Iringa City' => [
                'Gangilonga', 'Kihesa', 'Mkwawa', 'Mwangata', 'Ruaha'
            ],
            'Kilolo' => [
                'Idete', 'Ilula', 'Irole', 'Mlafu', 'Udekwa'
            ],
            'Mufindi' => [
                'Ifwagi', 'Kasanga', 'Mdabulo', 'Mapanda', 'Igowole'
            ],
            'Njombe' => [
                'Lupembe', 'Njombe Mjini', 'Ramadhani', 'Kibena', 'Kifanya'
            ],
            'Ludewa' => [
                'Ludewa Mjini', 'Manda', 'Milo', 'Masasi', 'Makonde'
            ],
            'Makete' => [
                'Ikuwo', 'Lupalilo', 'Matamba', 'Mfumbi', 'Ujuni'
            ],


            // Define wards for each district in Kagera region

            'Bukoba Town' => [
                'Hamugembe', 'Kashai', 'Nshambya', 'Nyanga', 'Rwamishenye'
            ],
            'Biharamulo' => [
                'Biharamulo Mjini', 'Nyakanazi', 'Nyarubungo', 'Ruziba', 'Runazi'
            ],
            'Chato' => [
                'Buseresere', 'Bwanga', 'Kakora', 'Nyamirembe', 'Chato Mjini'
            ],
            'Karagwe' => [
                'Kayanga', 'Nyakahanga', 'Bugene', 'Ihembe', 'Rugu'
            ],
            'Muleba' => [
                'Muleba Mjini', 'Kamachumu', 'Nshamba', 'Kasharunga', 'Rubya'
            ],
            'Ngara' => [
                'Ngara Mjini', 'Rulenge', 'Murusagamba', 'Kasulo', 'Kanazi'
            ],


            // Define wards for each district in Kigoma region
            'Kigoma Town' => [
                'Bangwe', 'Gungu', 'Kagera', 'Kigoma', 'Machinjioni'
            ],
            'Buhigwe' => [
                'Buhigwe', 'Muyama', 'Mugera', 'Nyamugali', 'Kilelema'
            ],
            'Kasulu' => [
                'Kasulu Mjini', 'Murubona', 'Heru Juu', 'Ruhita', 'Nyachenda'
            ],
            'Kibondo' => [
                'Kibondo Mjini', 'Kitahana', 'Rugongwe', 'Kumsenga', 'Kibonde'
            ],
            'Uvinza' => [
                'Uvinza Mjini', 'Ilagala', 'Sigunga', 'Sunuka', 'Buhingu'
            ],


            // Define wards for each district in Kilimanjaro region

            'Moshi Town' => [
                'Njoro', 'Kiusa', 'Mawenzi', 'Bondeni', 'Shanty Town'
            ],
            'Hai' => [
                'Bomang’ombe', 'Masama', 'Machame', 'Ng’uni', 'Kia'
            ],
            'Same' => [
                'Same Mjini', 'Ndungu', 'Makanya', 'Vunta', 'Chome'
            ],
            'Rombo' => [
                'Tarakea', 'Mashati', 'Kelamfua Mokala', 'Kirongo', 'Keni'
            ],
            'Siha' => [
                'Sanya Juu', 'Lawate', 'Biriri', 'Nasai', 'Ndumeti'
            ],
            'Mwanga' => [
                'Mwanga Mjini', 'Kifula', 'Lembeni', 'Kirya', 'Jipe'
            ],


            // Define wards for each district in Lindi region

            'Lindi Town' => [
                'Mikumbi', 'Kilimahewa', 'Mwandiga', 'Mtua', 'Nyanjembene'
            ],
            'Kilwa' => [
                'Kilwa Kivinje', 'Kilwa Masoko', 'Rombo', 'Kikwetu', 'Mikindani'
            ],
            'Liwale' => [
                'Liwale Mjini', 'Rufiji', 'Ruangwa', 'Mbagala', 'Lihuweti'
            ],
            'Nachingwea' => [
                'Nachingwea Mjini', 'Nyangao', 'Machi', 'Mikumbi', 'Namtumbo'
            ],
            'Ruangwa' => [
                'Ruangwa Mjini', 'Lindi', 'Mchinga', 'Kangundi', 'Kangoma'
            ],


            // Define wards for each district in Mara region

            'Musoma Town' => [
                'Kwerekwe', 'Mwisenge', 'Mugumu', 'Nyamagongo', 'Shinyanga'
            ],
            'Bunda' => [
                'Bunda Town', 'Bunda Mjini', 'Mwigobera', 'Wami', 'Kyamwilu'
            ],
            'Tarime' => [
                'Tarime Town', 'Sirari', 'Nyamongo', 'Kewanja', 'Simbiti'
            ],
            'Serengeti' => [
                'Seronera', 'Ikoma', 'Kongwa', 'Nyangwale', 'Matongo'
            ],
            'Rorya' => [
                'Rorya Town', 'Nyamwezi', 'Nkenge', 'Rugongo', 'Kasoroni'
            ],
            'Butiama' => [
                'Butiama Town', 'Bukima', 'Nyatwiga', 'Bujora', 'Mwamgongo'
            ],


            // Define wards for each district in Mbeya region

            'Mbeya City' => [
                'Mbalizi', 'Isyesye', 'Ilembo', 'Shikuyu', 'Iganjo'
            ],
            'Mbozi' => [
                'Mbozi Town', 'Mwakibete', 'Vwawa', 'Ipembe', 'Chitete'
            ],
            'Ileje' => [
                'Ileje Town', 'Shiwili', 'Sakania', 'Kilema', 'Ikwiriri'
            ],
            'Rungwe' => [
                'Rungwe Town', 'Tukuyu', 'Mwakibete', 'Lufilya', 'Mikola'
            ],
            'Chunya' => [
                'Chunya Town', 'Kandete', 'Mbande', 'Itumba', 'Mkwajuni'
            ],
            'Mbeya' => [
                'Mbeya Mjini', 'Ilomba', 'Mbalizi', 'Ilembo', 'Isyesye'
            ],


            // Define wards for each district in Morogoro region

            'Morogoro City' => [
                'Kihonda', 'Mji Mpya', 'Mazimbu', 'Mzumbe', 'Mfalali'
            ],
            'Mvomero' => [
                'Mvomero Town', 'Mlandizi', 'Mgeta', 'Kanga', 'Mvomero'
            ],
            'Kilosa' => [
                'Kilosa Town', 'Kibungo', 'Kivulini', 'Nguvumali', 'Mikumi'
            ],
            'Gairo' => [
                'Gairo Town', 'Mgeta', 'Mwaya', 'Kangeta', 'Dunda'
            ],
            'Ulanga' => [
                'Ulanga Town', 'Mtopwa', 'Matombo', 'Kipera', 'Matomondo'
            ],
            'Mahenge' => [
                'Mahenge Town', 'Utengule', 'Kasanga', 'Nkalango', 'Nduguti'
            ],


            // Define wards for each district in Mtwara region

            'Mtwara Town' => [
                'Mtwara Mjini', 'Kivukoni', 'Mikindani', 'Mwembeni', 'Chikongola'
            ],
            'Newala' => [
                'Newala Town', 'Tingi', 'Chimala', 'Ngana', 'Msijute'
            ],
            'Nanyumbu' => [
                'Nanyumbu Town', 'Mtopepo', 'Mikongeni', 'Mkwaja', 'Nanganga'
            ],
            'Masasi' => [
                'Masasi Town', 'Nanyumbu', 'Mkululu', 'Liwale', 'Machi'
            ],
            'Tandahimba' => [
                'Tandahimba Town', 'Mkululu', 'Mlimba', 'Mbuzi', 'Chihanga'
            ],

            // Define wards for each district in Mwanza region

            'Nyamagana' => [
                'Pamba', 'Mabatini', 'Ilemela', 'Nyamagana'
            ],
            'Misungwi' => [
                'Misungwi', 'Mwagili', 'Ilenge', 'Mlinga'
            ],
            'Magu' => [
                'Magu', 'Muganza', 'Kasembe', 'Bujashi', 'Mondo'
            ],
            'Ilemela' => [
                'Ilemela', 'Buswelu', 'Buzuruga', 'Mecco', 'Shadi', 'Mabatini'
            ],
            'Kwimba' => [
                'Kwimba', 'Bwiru', 'Mwandiga', 'Nshambya', 'Ibungilo'
            ],
            'Sengerema' => [
                'Sengerema', 'Sungusilo', 'Mwadui', 'Katoro', 'Butimba'
            ],


            // Define wards for each district in Njombe region

            'Njombe Town' => [
                'Njombe Mjini', 'Kigonwe', 'Manda', 'Kitonga', 'Kikwawila'
            ],
            'Ludewa' => [
                'Ludewa Town', 'Mlala', 'Matembwe', 'Isalamuro', 'Mbugani'
            ],
            'Makete' => [
                'Makete Town', 'Lupembe', 'Makwale', 'Nyololo', 'Tunduma'
            ],
            "Wanging’ombe" => [
                "Wanging’ombe Town", 'Ilonga', 'Matembwe', 'Sumbawanga', 'Mwenge'
            ],
            'Ruaha' => [
                'Ruaha Town', 'Ruangwa', 'Mbimba', 'Ludewa', 'Tunduma'
            ],


            // Define wards for each district in Pemba North region

            'Wete' => [
                'Wete Town', 'Kengeja', 'Mchangani', 'Pemba', 'Chake Chake'
            ],
            'Micheweni' => [
                'Micheweni Town', 'Mtepeni', 'Mtambile', 'Pemba North', 'Mkalamo'
            ],
            'Chake Chake' => [
                'Chake Chake Town', 'Kengeja', 'Mwangala', 'Pemba', 'Mchemo'
            ],
            'Pemba South' => [
                'Pemba South Town', 'Kisiwa', 'Mitumbuni', 'Mselemu', 'Milele'
            ],


            // Define wards for each district in Pemba South region

            'Mkoani' => [
                'Mkoani Town', 'Tunduni', 'Kisiwa', 'Makoba', 'Wete'
            ],
            'Chake Chake' => [
                'Chake Chake Town', 'Kengeja', 'Mwangala', 'Pemba', 'Mchemo'
            ],
            'Wete' => [
                'Wete Town', 'Mchangani', 'Kengeja', 'Chake Chake', 'Pemba North'
            ],


            // Define wards for each district in Rukwa region

            'Sumbawanga' => [
                'Sumbawanga Town', 'Kipeta', 'Mamboya', 'Lugunga', 'Kasesa'
            ],
            'Nkasi' => [
                'Nkasi Town', 'Kasanga', 'Mpanda', 'Inyonga', 'Lugalo'
            ],
            'Kalambo' => [
                'Kalambo Town', 'Ilembo', 'Sakala', 'Mlele', 'Kasesa'
            ],
            'Sumbawanga Town' => [
                'Sumbawanga Town', 'Katuma', 'Kiwira', 'Sumbawanga Urban'
            ],


            // Define wards for each district in Ruvuma region

            'Songea' => [
                'Songea Town', 'Majengo', 'Mjimwema', 'Songea Urban', 'Matetereka'
            ],
            'Mbinga' => [
                'Mbinga Town', 'Nyamwage', 'Tunduma', 'Lundusi', 'Namatwe'
            ],
            'Namtumbo' => [
                'Namtumbo Town', 'Mbinga', 'Chilala', 'Chiwanda', 'Mkowe'
            ],
            'Tunduru' => [
                'Tunduru Town', 'Lugumi', 'Mlenge', 'Chitoa', 'Matogoro'
            ],
            'Nyasa' => [
                'Nyasa Town', 'Nchinga', 'Tunduru', 'Mlimba', 'Mwandiga'
            ],


            // Define wards for each district in Shinyanga region

            'Shinyanga City' => [
                'Kahama Town', 'Lwenge', 'Shinyanga Urban', 'Nhelegani', 'Isuna'
            ],
            'Kahama' => [
                'Kahama Town', 'Mwendakulima', 'Chamanzi', 'Igagala', 'Kitangili'
            ],
            'Maswa' => [
                'Maswa Town', 'Haluza', 'Mapogoro', 'Buhongwa', 'Tunduma'
            ],
            'Shinyanga District' => [
                'Shinyanga Town', 'Mwamishiga', 'Lulindi', 'Mbulu', 'Ilemela'
            ],


            // Define wards for each district in Simiyu region

            'Bariadi' => [
                'Bariadi Town', 'Bujashi', 'Bugarama', 'Masangula', 'Nyashimo'
            ],
            'Meatu' => [
                'Meatu Town', 'Ikizu', 'Tunguli', 'Matongo', 'Luguru'
            ],
            'Itilima' => [
                'Itilima Town', 'Kasoma', 'Mwigobero', 'Kiroba', 'Maswa'
            ],
            'Maswa' => [
                'Maswa Town', 'Haluza', 'Buhongwa', 'Tunduma', 'Chigunga'
            ],


            // Define wards for each district in Singida region

            'Singida Town' => [
                'Singida Town Center', 'Ilonga', 'Makiungu', 'Mikuyu', 'Mwanyambo'
            ],
            'Ikungi' => [
                'Ikungi Town', 'Katesh', 'Kintinku', 'Nduguti', 'Mughanga'
            ],
            'Mkalama' => [
                'Mkalama Town', 'Nkunyamila', 'Bwiru', 'Mwasabuka', 'Mtitaa'
            ],
            'Manyoni' => [
                'Manyoni Town', 'Chikola', 'Msemembo', 'Mishamo', 'Mnyera'
            ],
            'Singida District' => [
                'Kishiri', 'Duma', 'Bungu', 'Kinyangiri', 'Nzuguni'
            ],


            // Define wards for each district in Tabora region

            'Tabora City' => [
                'Tabora Town', 'Kiloleni', 'Utemini', 'Isamilo', 'Nyamisati'
            ],
            'Urambo' => [
                'Urambo Town', 'Mwadui', 'Kahama', 'Tumbi', 'Kizumbi'
            ],
            'Nzega' => [
                'Nzega Town', 'Chonga', 'Tabora Urban', 'Bunge', 'Nsimbo'
            ],
            'Igunga' => [
                'Igunga Town', 'Sikonge', 'Igunga Rural', 'Iduo', 'Mbamaga'
            ],
            'Tabora Rural' => [
                'Mwamashimba', 'Chikonko', 'Lupembe', 'Kigwangala', 'Kilombero'
            ],


            // Define wards for each district in Tanga region

            'Tanga City' => [
                'Tanga Town', 'Mwakidila', 'Mkabawa', 'Chumbageni', 'Magogoni'
            ],
            'Muheza' => [
                'Muheza Town', 'Kange', 'Ugweno', 'Soni', 'Kichangani'
            ],
            'Handeni' => [
                'Handeni Town', 'Kongwa', 'Chumbageni', 'Ngwelo', 'Mkata'
            ],
            'Pangani' => [
                'Pangani Town', 'Pangani District', 'Tanga Beach', 'Kunduchi', 'Vikindu'
            ],
            'Korogwe' => [
                'Korogwe Town', 'Msomera', 'Mlale', 'Lulenge', 'Mgwashi'
            ],
            'Lushoto' => [
                'Lushoto Town', 'Sambamba', 'Bumbuli', 'Amani', 'Mlalo'
            ],
        ];

        // Insert wards into the database
        foreach ($wardsData as $districtName => $wards) {
            $district = DB::table('districts')->where('name', $districtName)->first();

            if ($district) {
                foreach ($wards as $ward) {
                    DB::table('wards')->insert([
                        'name' => $ward,
                        'district_id' => $district->id,
                    ]);
                }
            }
        }
    
    }
}
