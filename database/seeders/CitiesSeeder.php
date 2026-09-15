<?php

namespace Database\Seeders;

use App\Models\Cities;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // cities
        $cities = [
            ['name' => 'Bhaktapur', 'country' => 'Nepal'],
            ['name' => 'Bharatpur', 'country' => 'Nepal'],
            ['name' => 'Biratnagar', 'country' => 'Nepal'],
            ['name' => 'Birgunj', 'country' => 'Nepal'],
            ['name' => 'Butwal', 'country' => 'Nepal'],
            ['name' => 'Dhangadhi', 'country' => 'Nepal'],
            ['name' => 'Dharan', 'country' => 'Nepal'],
            ['name' => 'Ghorahi', 'country' => 'Nepal'],
            ['name' => 'Hetauda', 'country' => 'Nepal'],
            ['name' => 'Itahari', 'country' => 'Nepal'],
            ['name' => 'Janakpur', 'country' => 'Nepal'],
            ['name' => 'Jitpursimara', 'country' => 'Nepal'],
            ['name' => 'Kalaiya', 'country' => 'Nepal'],
            ['name' => 'Kathmandu', 'country' => 'Nepal'],
            ['name' => 'Lalitpur', 'country' => 'Nepal'],
            ['name' => 'Nepalgunj', 'country' => 'Nepal'],
            ['name' => 'Pokhara', 'country' => 'Nepal'],
            ['name' => 'Tulsipur', 'country' => 'Nepal'],
        ];

        // loop through the cities list
        foreach ($cities as $cities) {
            Cities::create($cities);
        }
    }
}
