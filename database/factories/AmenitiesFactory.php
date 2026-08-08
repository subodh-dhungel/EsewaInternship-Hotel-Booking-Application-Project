<?php

namespace Database\Factories;

use App\Models\Amenities;
use Illuminate\Database\Eloquent\Factories\Factory;

class AmenitiesFactory extends Factory
{
    protected $model = Amenities::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->randomElement([
                'Free WiFi',
                'Swimming Pool',
                'Parking',
                'Air Conditioning',
                'Restaurant',
                'Room Service',
                'Gym',
                'Spa',
                '24/7 Reception',
                'Breakfast',
                'Airport Shuttle',
                'Laundry',
                'Pet Friendly',
                'Bar',
                'Conference Room',
            ]),

            'icon' => fake()->randomElement([
                'wifi',
                'pool',
                'parking',
                'ac',
                'restaurant',
                'room-service',
                'gym',
                'spa',
                'reception',
                'breakfast',
            ]),
        ];
    }
}