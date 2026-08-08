<?php

namespace Database\Seeders;

use App\Models\Amenities;
use App\Models\Hotel;
use App\Models\HotelImages;
use App\Models\RoomImages;
use App\Models\RoomTypes;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        //permissions
        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
            RolePermissionSeeder::class,
            UserRoleSeeder::class,
        ]);

        //users
        $users = User::factory()->count(10)->create();

        //hotels
        $hotels = Hotel::factory()
            ->count(20)
            ->recycle($users)
            ->create();

        //hotel images
        foreach($hotels as $hotel){
            HotelImages::factory()
            ->count(rand(3,6))
            ->create([
                'hotel_id' => $hotel->id,
            ]);
        }

        // room types
        
        foreach($hotels as $hotel){
            RoomTypes::factory()
            ->count(rand(2,5))
            ->create([
                'hotel_id'=>$hotel->id,
            ]);
        }

        // room images
        $roomTypes = RoomTypes::all();

        foreach($roomTypes as $roomType) {
            RoomImages::factory()
            ->count(rand(2,4))
            ->create([
                'room_type_id'=>$roomType->id,
            ]);
        }

        // Amenities
        $amenities = Amenities::factory()
            ->count(10)
            ->create();

        // Hotel <-> Amenities
        foreach($hotels as $hotel){
            $hotel->amenity()->attach(
                $amenities
                    ->random(rand(2,6))
                    ->pluck('id')
            );
        }
    }
}
